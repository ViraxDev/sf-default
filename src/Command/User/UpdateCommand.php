<?php
declare(strict_types=1);

namespace App\Command\User;

use App\Document\User;
use App\Enum\UserRoleEnum;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:update',
    description: 'Create a user with an appropriate role',
)]
final class UpdateCommand extends Command
{
    public function __construct(private readonly DocumentManager $documentManager, private readonly UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'user\'s email')
            ->addArgument('password', InputArgument::OPTIONAL, 'user\'s PASSWORD')
            ->addArgument('role', InputArgument::OPTIONAL, 'user\'s role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');

        if ($role && !in_array($role, $roles = UserRoleEnum::getRoles())) {
            $io->error(sprintf('The role %s should be one of [%s]', $role, implode(',', $roles)));

            return Command::FAILURE;
        }

        /** @var User $user */
        if (!($user = $this->documentManager->getRepository(User::class)->findOneBy(compact('email')))) {
            $io->error(sprintf('The user %s doesn\'t exist.', $email));

            return Command::FAILURE;
        }

        if ($password) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        if ($role) {
            $user->setRoles([$role]);
        }

        $this->documentManager->flush();

        $io->success(sprintf('User %s updated successfully', $email));

        return Command::SUCCESS;
    }
}
