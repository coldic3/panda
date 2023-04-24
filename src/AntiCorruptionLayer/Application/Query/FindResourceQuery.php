<?php

declare(strict_types=1);

namespace Panda\AntiCorruptionLayer\Application\Query;

use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindResourceQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
