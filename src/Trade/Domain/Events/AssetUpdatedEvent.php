<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Events;

use Panda\Core\Domain\Event\EventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class AssetUpdatedEvent implements EventInterface
{
    public function __construct(public Uuid $assetId)
    {
    }
}
