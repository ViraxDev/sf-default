<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

final class PasswordFormType extends AbstractType
{
    public function __construct(
        #[Autowire(env: 'APP_ENV')]
        private readonly string $env
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $passwordConstraint = [new NotBlank()];

        if (!in_array($this->env, ['test', 'dev'], true)) {
            array_push($passwordConstraint, new PasswordStrength(), new NotCompromisedPassword());
        }

        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => ['constraints' => $passwordConstraint],
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['inherit_data' => true]);
    }
}
