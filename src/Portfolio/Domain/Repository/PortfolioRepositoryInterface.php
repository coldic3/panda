<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Repository;

use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Shared\Domain\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

interface PortfolioRepositoryInterface extends RepositoryInterface
{
    public function save(PortfolioInterface $portfolio): void;

    public function remove(PortfolioInterface $portfolio): void;

    public function findById(Uuid $id): ?PortfolioInterface;
}
