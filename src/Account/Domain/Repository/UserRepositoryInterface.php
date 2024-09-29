<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Repository;

use Panda\Account\Domain\Model\UserInterface;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function save(UserInterface $user): void;

    public function remove(UserInterface $user): void;

    public function findById(Uuid $id): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;
}
