<?php

declare(strict_types=1);

namespace App\Account\Domain\Factory;

use App\Account\Domain\Model\User;
use App\Account\Domain\Model\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFactory implements UserFactoryInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create(string $email, ?string $password = null): UserInterface
    {
        $user = new User($email);

        if (null !== $password) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        }

        return $user;
    }
}
