<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

final readonly class Operation implements OperationInterface
{
    public function __construct(private string $resourceId, private int $quantity)
    {
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
