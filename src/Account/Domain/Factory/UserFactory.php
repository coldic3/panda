<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Factory;

use Panda\Account\Domain\Model\User;
use Panda\Account\Domain\Model\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create(string $email, string $password = null): UserInterface
    {
        $user = new User($email);

        if (null !== $password) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        }

        return $user;
    }
}
