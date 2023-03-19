<?php

declare(strict_types=1);

namespace Panda\Reception\Domain\Repository;

use Panda\Reception\Domain\Model\Greeting;
use Panda\Shared\Domain\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

interface GreetingRepositoryInterface extends RepositoryInterface
{
    public function save(Greeting $greeting): void;

    public function remove(Greeting $greeting): void;

    public function findById(Uuid $id): ?Greeting;

    public function likeName(string $name): static;
}
