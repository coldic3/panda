<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Model;

use Panda\Shared\Domain\Model\IdentifiableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends IdentifiableInterface, SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function setPassword(string $password): void;

    public function compare(UserInterface $user): bool;
}
