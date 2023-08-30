<?php

declare(strict_types=1);

namespace Panda\Tests\Fixture\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItem;
use Panda\Portfolio\Domain\ValueObject\Resource;

final class PortfolioItemFactory
{
    public static function create(
        string $resourceTicker,
        string $resourceName,
        PortfolioInterface $portfolio,
    ): PortfolioItem {
        $resource = new Resource($resourceTicker, $resourceName);

        return new PortfolioItem($resource, $portfolio);
    }
}
