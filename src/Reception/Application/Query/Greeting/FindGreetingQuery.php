<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Query\Greeting;

use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindGreetingQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
