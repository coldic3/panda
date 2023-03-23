<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Repository;

use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Shared\Domain\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

interface AssetRepositoryInterface extends RepositoryInterface
{
    public function save(AssetInterface $asset): void;

    public function remove(AssetInterface $asset): void;

    public function findById(Uuid $id): ?AssetInterface;
}
