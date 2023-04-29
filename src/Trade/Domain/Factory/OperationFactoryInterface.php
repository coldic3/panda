<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

interface OperationFactoryInterface
{
    public function create(ResourceInterface $resource, int $quantity): OperationInterface;
}
