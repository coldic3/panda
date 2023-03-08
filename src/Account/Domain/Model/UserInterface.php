<?php

declare(strict_types=1);

namespace App\Account\Domain\Model;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function setPassword(string $password): void;
}
