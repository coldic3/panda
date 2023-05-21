<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItem;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
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
