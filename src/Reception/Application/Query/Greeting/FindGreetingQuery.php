<?php

declare(strict_types=1);

namespace App\Reception\Application\Query\Greeting;

use App\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindGreetingQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
