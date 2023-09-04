<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;

interface PortfolioItemFactoryInterface
{
    public function create(
        string $resourceTicker,
        string $resourceName,
        PortfolioInterface $portfolio
    ): PortfolioItemInterface;
}
