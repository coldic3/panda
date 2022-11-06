<?php

declare(strict_types=1);

namespace App\Reception\Application\Query\Greeting;

use App\Shared\Application\Query\QueryInterface;

final class FindGreetingsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $page = null,
        public readonly ?int $itemsPerPage = null,
    ) {
    }
}
