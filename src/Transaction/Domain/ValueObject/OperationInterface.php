<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

interface OperationInterface
{
    public function getResourceId(): string;

    public function getQuantity(): int;
}
