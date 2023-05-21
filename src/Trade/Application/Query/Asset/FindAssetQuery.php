<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Asset;

use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindAssetQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
