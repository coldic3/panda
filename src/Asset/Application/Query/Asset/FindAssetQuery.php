<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindAssetQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
