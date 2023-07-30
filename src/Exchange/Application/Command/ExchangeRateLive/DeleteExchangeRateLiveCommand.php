<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DeleteExchangeRateLiveCommand implements CommandInterface
{
    public function __construct(public Uuid $id)
    {
    }
}
