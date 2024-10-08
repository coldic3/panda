<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;

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
