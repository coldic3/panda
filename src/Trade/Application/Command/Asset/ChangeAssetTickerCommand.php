<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ChangeAssetTickerCommand implements CommandInterface
{
    public function __construct(public Uuid $id, public string $ticker)
    {
    }
}
