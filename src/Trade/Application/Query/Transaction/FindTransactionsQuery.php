<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Transaction;

use Panda\Core\Application\Query\QueryInterface;

final readonly class FindTransactionsQuery implements QueryInterface
{
    public function __construct(
        public ?string $fromOperationAssetId = null,
        public ?string $toOperationAssetId = null,
        public ?\DateTimeImmutable $afterConcludedAt = null,
        public ?\DateTimeImmutable $beforeConcludedAt = null,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
