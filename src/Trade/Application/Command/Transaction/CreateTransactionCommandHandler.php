<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
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
