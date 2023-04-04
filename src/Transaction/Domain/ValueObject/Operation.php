<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;

final readonly class Operation implements OperationInterface
{
    public function __construct(
        private ResourceInterface $resource,
        private int $quantity,
    ) {
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
