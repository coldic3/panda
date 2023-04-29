<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Shared\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final class DeleteAssetCommand implements CommandInterface
{
    public function __construct(public readonly Uuid $id)
    {
    }
}
