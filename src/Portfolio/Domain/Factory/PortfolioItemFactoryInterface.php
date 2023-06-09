<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;

interface PortfolioItemFactoryInterface
{
    public function create(
        string $resourceTicker,
        string $resourceName,
        PortfolioInterface $portfolio
    ): PortfolioItemInterface;
}
