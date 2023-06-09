<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Repository;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Shared\Domain\Repository\QueryInterface;
use Panda\Shared\Domain\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

interface PortfolioRepositoryInterface extends RepositoryInterface
{
    public function save(PortfolioInterface $portfolio): void;

    public function remove(PortfolioInterface $portfolio): void;

    public function findById(Uuid $id): ?PortfolioInterface;

    public function findDefault(): ?PortfolioInterface;

    public function defaultExists(): bool;

    public function defaultQuery(OwnerInterface $owner): QueryInterface;
}
