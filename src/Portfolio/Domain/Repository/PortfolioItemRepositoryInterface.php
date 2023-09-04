<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Repository;

use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;

interface PortfolioItemRepositoryInterface extends RepositoryInterface
{
    public function save(PortfolioItemInterface $portfolioItem): void;

    public function findByTickerWithinPortfolio(string $ticker, PortfolioInterface $portfolio): ?PortfolioItemInterface;
}
