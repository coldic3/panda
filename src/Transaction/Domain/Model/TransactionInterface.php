<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\Model;

use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Transaction\Domain\Enum\TransactionTypeEnum;
use Panda\Transaction\Domain\ValueObject\OperationInterface;

interface TransactionInterface extends IdentifiableInterface
{
    public function getType(): TransactionTypeEnum;

    public function getFromOperation(): OperationInterface;

    public function getToOperation(): OperationInterface;

    /** @return OperationInterface[] */
    public function getAdjustmentOperations(): array;

    public function getConcludedAt(): \DateTimeInterface;
}
