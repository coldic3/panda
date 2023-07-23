<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRate;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateExchangeRateCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public ?float $rate = null,
    ) {
    }
}
