<?php

declare(strict_types=1);

namespace Panda\PortfolioOHS\Application\Resolver;

use Panda\Portfolio\Application\Exception\PortfolioNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;

interface PortfolioResolverInterface
{
    /** @throws PortfolioNotFoundException */
    public function resolve(): PortfolioInterface;
}
