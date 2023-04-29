<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Transaction;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;

interface OperationInterface extends IdentifiableInterface
{
    public function getResource(): ResourceInterface;

    public function getQuantity(): int;
}
