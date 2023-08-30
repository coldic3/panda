<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Repository;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
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
