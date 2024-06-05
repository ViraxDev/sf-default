<?php
declare(strict_types=1);

namespace App\Twig\Runtime;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class UserExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private Security $security,
        #[Autowire(service: 'twig.extension.assets')]
        private AssetExtension $assetExtension
    ) {
    }

    public function getUserPicture(string $type): string
    {
        $user = $this->security->getUser();
        $defaultPicture = match ($type) {
            'pictureProfile' => 'theme/img/team/avatar.png',
            'coverPictureProfile' => 'theme/img/generic/4.jpg',
        };

        $getter = sprintf('get%s', ucfirst($type));

        if (method_exists($user, $getter) && !empty($value = $user->{$getter}())) {
            return $this->assetExtension->getAssetUrl(sprintf('img/uploads/%s', $value));
        }

        return $this->assetExtension->getAssetUrl($defaultPicture);
    }
}
