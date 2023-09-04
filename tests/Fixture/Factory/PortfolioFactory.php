<?php

declare(strict_types=1);

namespace Panda\Tests\Fixture\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\Portfolio;
use Panda\Portfolio\Domain\ValueObject\Resource;

final class PortfolioFactory
{
    public static function create(
        string $name,
        string $mainResourceTicker,
        string $mainResourceName,
        bool $default,
    ): Portfolio {
        $resource = new Resource($mainResourceTicker, $mainResourceName);

        return new Portfolio($name, $resource, $default);
    }
}
