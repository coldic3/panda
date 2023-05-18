<?php

declare(strict_types=1);

namespace Panda\Tests\Fixture\Factory;

use Panda\Account\Domain\Model\UserInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\Transaction\Operation;
use Panda\Trade\Domain\Model\Transaction\Transaction;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;

final class TransactionFactory
{
    public static function createAsk(
        AssetInterface $fromAsset,
        int $fromQuantity,
        AssetInterface $toAsset,
        int $toQuantity,
        AssetInterface $adjustmentAsset,
        int $adjustmentQuantity,
        \DateTimeImmutable $concludedAt,
        UserInterface $user,
    ): Transaction {
        $fromOperation = new Operation($fromAsset, $fromQuantity);
        $toOperation = new Operation($toAsset, $toQuantity);
        $adjustmentOperation = new Operation($adjustmentAsset, $adjustmentQuantity);

        $transaction = new Transaction(
            TransactionTypeEnum::ASK,
            $fromOperation,
            $toOperation,
            [$adjustmentOperation],
            $concludedAt,
        );

        $transaction->setOwnedBy($user);

        return $transaction;
    }

    public static function createBid(
        AssetInterface $fromAsset,
        int $fromQuantity,
        AssetInterface $toAsset,
        int $toQuantity,
        AssetInterface $adjustmentAsset,
        int $adjustmentQuantity,
        \DateTimeImmutable $concludedAt,
        UserInterface $user,
    ): Transaction {
        $fromOperation = new Operation($fromAsset, $fromQuantity);
        $toOperation = new Operation($toAsset, $toQuantity);
        $adjustmentOperation = new Operation($adjustmentAsset, $adjustmentQuantity);

        $transaction = new Transaction(
            TransactionTypeEnum::BID,
            $fromOperation,
            $toOperation,
            [$adjustmentOperation],
            $concludedAt,
        );

        $transaction->setOwnedBy($user);

        return $transaction;
    }

    public static function createDeposit(
        AssetInterface $toAsset,
        int $toQuantity,
        AssetInterface $adjustmentAsset,
        int $adjustmentQuantity,
        \DateTimeImmutable $concludedAt,
        UserInterface $user,
    ): Transaction {
        $toOperation = new Operation($toAsset, $toQuantity);
        $adjustmentOperation = new Operation($adjustmentAsset, $adjustmentQuantity);

        $transaction = new Transaction(
            TransactionTypeEnum::DEPOSIT,
            null,
            $toOperation,
            [$adjustmentOperation],
            $concludedAt,
        );

        $transaction->setOwnedBy($user);

        return $transaction;
    }

    public static function createWithdraw(
        AssetInterface $fromAsset,
        int $fromQuantity,
        AssetInterface $adjustmentAsset,
        int $adjustmentQuantity,
        \DateTimeImmutable $concludedAt,
        UserInterface $user,
    ): Transaction {
        $fromOperation = new Operation($fromAsset, $fromQuantity);
        $adjustmentOperation = new Operation($adjustmentAsset, $adjustmentQuantity);

        $transaction = new Transaction(
            TransactionTypeEnum::WITHDRAW,
            $fromOperation,
            null,
            [$adjustmentOperation],
            $concludedAt,
        );

        $transaction->setOwnedBy($user);

        return $transaction;
    }

    public static function createFee(
        AssetInterface $adjustmentAsset,
        int $adjustmentQuantity,
        \DateTimeImmutable $concludedAt,
        UserInterface $user,
    ): Transaction {
        $adjustmentOperation = new Operation($adjustmentAsset, $adjustmentQuantity);

        $transaction = new Transaction(
            TransactionTypeEnum::FEE,
            null,
            null,
            [$adjustmentOperation],
            $concludedAt,
        );

        $transaction->setOwnedBy($user);

        return $transaction;
    }
}
