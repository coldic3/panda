<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Query\Greeting;

use Panda\Shared\Application\Query\QueryInterface;

final class FindGreetingsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $page = null,
        public readonly ?int $itemsPerPage = null,
    ) {
    }
}
