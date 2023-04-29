<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Symfony\Component\Uid\Uuid;

final readonly class Operation implements OperationInterface
{
    private Uuid $id;

    public function __construct(
        private ResourceInterface $resource,
        private int $quantity,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
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
