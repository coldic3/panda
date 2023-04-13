<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\Factory;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Transaction\Domain\Model\OperationInterface;
use Panda\Transaction\Domain\Model\Transaction;
use Panda\Transaction\Domain\Model\TransactionInterface;
use Panda\Transaction\Domain\ValueObject\TransactionTypeEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class TransactionFactory implements TransactionFactoryInterface
{
    public function __construct(private readonly Security $security)
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
        Assert::notEmpty($adjustments);

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

        Assert::isInstanceOf(
            $owner = $this->security->getUser(),
            OwnerInterface::class
        );

        $transaction->setOwnedBy($owner);

        return $transaction;
    }
}
