<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Repository;

use Panda\Shared\Domain\Repository\RepositoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Symfony\Component\Uid\Uuid;

interface AssetRepositoryInterface extends RepositoryInterface
{
    public function save(AssetInterface $asset): void;

    public function remove(AssetInterface $asset): void;

    public function findById(Uuid $id): ?AssetInterface;
}
