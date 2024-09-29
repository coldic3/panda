<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Model\TimestampableInterface;

interface UserInterface extends TimestampableInterface, OwnerInterface
{
    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getPassword(): ?string;

    public function setPassword(string $password): void;
}
