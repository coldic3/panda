<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;

interface PortfolioFactoryInterface
{
    public function create(string $name, bool $default = false, OwnerInterface $owner = null): PortfolioInterface;
}
