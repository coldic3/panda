<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Trade\Domain\Model\Transaction\Operation;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;

final class OperationFactory implements OperationFactoryInterface
{
    public function create(ResourceInterface $resource, int $quantity): OperationInterface
    {
        return new Operation($resource, $quantity);
    }
}
