<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Trade\Domain\Exception\EmptyAdjustmentsException;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Panda\Trade\Domain\Model\Transaction\Transaction;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;

final class TransactionFactory implements TransactionFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function createAsk(
        OperationInterface $from,
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface {
        $transaction = new Transaction(
            TransactionTypeEnum::ASK,
            $from,
            $to,
            $adjustments,
            $concludedAt,
        );

        return $this->enrichWithOwner($transaction, $owner);
    }

    public function createBid(
        OperationInterface $from,
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface {
        $transaction = new Transaction(
            TransactionTypeEnum::BID,
            $from,
            $to,
            $adjustments,
            $concludedAt,
        );

        return $this->enrichWithOwner($transaction, $owner);
    }

    public function createDeposit(
        OperationInterface $to,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface {
        $transaction = new Transaction(
            TransactionTypeEnum::DEPOSIT,
            null,
            $to,
            $adjustments,
            $concludedAt,
        );

        return $this->enrichWithOwner($transaction, $owner);
    }

    public function createWithdraw(
        OperationInterface $from,
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface {
        $transaction = new Transaction(
            TransactionTypeEnum::WITHDRAW,
            $from,
            null,
            $adjustments,
            $concludedAt,
        );

        return $this->enrichWithOwner($transaction, $owner);
    }

    public function createFee(
        array $adjustments,
        \DateTimeInterface $concludedAt,
        ?OwnerInterface $owner = null,
    ): TransactionInterface {
        if ([] === $adjustments) {
            throw new EmptyAdjustmentsException();
        }

        $transaction = new Transaction(
            TransactionTypeEnum::FEE,
            null,
            null,
            $adjustments,
            $concludedAt,
        );

        return $this->enrichWithOwner($transaction, $owner);
    }

    private function enrichWithOwner(TransactionInterface $transaction, ?OwnerInterface $owner): TransactionInterface
    {
        if (null !== $owner) {
            $transaction->setOwnedBy($owner);

            return $transaction;
        }

        $transaction->setOwnedBy($this->authorizedUserProvider->provide());

        return $transaction;
    }
}
