<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItem;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
use Panda\Portfolio\Domain\ValueObject\Resource;

final class PortfolioItemFactory implements PortfolioItemFactoryInterface
{
    public function create(
        string $resourceTicker,
        string $resourceName,
        PortfolioInterface $portfolio
    ): PortfolioItemInterface {
        return new PortfolioItem(new Resource($resourceTicker, $resourceName), $portfolio);
    }
}
