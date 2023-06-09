<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Shared\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class UpdatePortfolioCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public ?string $name = null,
    ) {
    }
}
