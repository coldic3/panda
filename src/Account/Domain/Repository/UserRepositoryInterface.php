<?php

declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Account\Domain\Model\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface extends PasswordUpgraderInterface
{
    public function save(UserInterface $user): void;

    public function remove(UserInterface $user): void;

    public function findById(Uuid $id): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;
}
