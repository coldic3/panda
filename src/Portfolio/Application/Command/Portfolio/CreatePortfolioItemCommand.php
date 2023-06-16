<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Core\Application\Command\CommandInterface;

final readonly class CreatePortfolioItemCommand implements CommandInterface
{
    public function __construct(
        public string $ticker,
        public string $name,
    ) {
    }
}
