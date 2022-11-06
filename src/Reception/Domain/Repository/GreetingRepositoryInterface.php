<?php

declare(strict_types=1);

namespace App\Reception\Domain\Repository;

use App\Reception\Domain\Model\Greeting;
use App\Shared\Domain\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends RepositoryInterface<Greeting>
 */
interface GreetingRepositoryInterface extends RepositoryInterface
{
    public function save(Greeting $greeting): void;

    public function remove(Greeting $greeting): void;

    public function findById(Uuid $id): ?Greeting;

    public function likeName(string $name): static;
}
