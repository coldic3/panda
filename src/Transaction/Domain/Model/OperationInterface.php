<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\Model;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;

interface OperationInterface extends IdentifiableInterface
{
    public function getResource(): ResourceInterface;

    public function getQuantity(): int;
}
