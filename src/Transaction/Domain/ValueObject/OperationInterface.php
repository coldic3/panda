<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;

interface OperationInterface
{
    public function getResource(): ResourceInterface;

    public function getQuantity(): int;
}
