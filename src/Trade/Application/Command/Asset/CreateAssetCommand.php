<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Shared\Application\Command\CommandInterface;

final readonly class CreateAssetCommand implements CommandInterface
{
    public function __construct(
        public string $ticker,
        public string $name,
    ) {
    }
}
