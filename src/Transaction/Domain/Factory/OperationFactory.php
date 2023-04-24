<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\Factory;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Transaction\Domain\Model\Operation;
use Panda\Transaction\Domain\Model\OperationInterface;

final class OperationFactory implements OperationFactoryInterface
{
    public function create(ResourceInterface $resource, int $quantity): OperationInterface
    {
        return new Operation($resource, $quantity);
    }
}
