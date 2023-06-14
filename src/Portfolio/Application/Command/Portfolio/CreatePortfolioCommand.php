<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Shared\Application\Command\CommandInterface;

final readonly class CreatePortfolioCommand implements CommandInterface
{
    public function __construct(public string $name)
    {
    }
}