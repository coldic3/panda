<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\Factory;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Transaction\Domain\Model\OperationInterface;
use Panda\Transaction\Domain\Model\TransactionInterface;

interface TransactionFactoryInterface
{
    /**
     * @param OperationInterface[] $adjustments
     */
    public function createAsk(
        OperationInterface $from,
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface;

    /**
     * @param OperationInterface[] $adjustments
     */
    public function createBid(
        OperationInterface $from,
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface;

    /**
     * @param OperationInterface[] $adjustments
     */
    public function createDeposit(
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface;

    /**
     * @param OperationInterface[] $adjustments
     */
    public function createWithdraw(
        OperationInterface $from,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface;

    /**
     * @param OperationInterface[] $adjustments
     */
    public function createFee(
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface;
}
