<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Transaction\Domain\Factory\TransactionFactoryInterface;
use Panda\Transaction\Domain\Model\TransactionInterface;
use Panda\Transaction\Domain\Repository\TransactionRepositoryInterface;
use Panda\Transaction\Domain\ValueObject\TransactionTypeEnum;
use Webmozart\Assert\Assert;

final class CreateTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly TransactionFactoryInterface $transactionFactory,
    ) {
    }

    public function __invoke(CreateTransactionCommand $command): TransactionInterface
    {
        switch ($command->type) {
            case TransactionTypeEnum::ASK:
                Assert::notNull($command->fromOperation);
                Assert::notNull($command->toOperation);

                $transaction = $this->transactionFactory->createAsk(
                    $command->fromOperation,
                    $command->toOperation,
                    $command->adjustmentOperations,
                    $command->concludedAt,
                );
                break;
            case TransactionTypeEnum::BID:
                Assert::notNull($command->fromOperation);
                Assert::notNull($command->toOperation);

                $transaction = $this->transactionFactory->createBid(
                    $command->fromOperation,
                    $command->toOperation,
                    $command->adjustmentOperations,
                    $command->concludedAt,
                );
                break;
            case TransactionTypeEnum::DEPOSIT:
                Assert::notNull($command->toOperation);

                $transaction = $this->transactionFactory->createDeposit(
                    $command->toOperation,
                    $command->adjustmentOperations,
                    $command->concludedAt,
                );
                break;
            case TransactionTypeEnum::WITHDRAW:
                Assert::notNull($command->fromOperation);

                $transaction = $this->transactionFactory->createWithdraw(
                    $command->fromOperation,
                    $command->adjustmentOperations,
                    $command->concludedAt,
                );
                break;
            case TransactionTypeEnum::FEE:
                $transaction = $this->transactionFactory->createFee(
                    $command->adjustmentOperations,
                    $command->concludedAt,
                );
                break;
            default:
                throw new \InvalidArgumentException('Invalid transaction type.');
        }

        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}
