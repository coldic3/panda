<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Domain\Model\PortfolioInterface;

interface PortfolioResolverInterface
{
    public function resolve(): PortfolioInterface;
}
