<?php
declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\UserExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_picture', [UserExtensionRuntime::class, 'getUserPicture']),
        ];
    }
}
