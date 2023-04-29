<?php

namespace spec\Panda\Trade\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommandHandler;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Model\OperationInterface;
use Panda\Trade\Domain\Model\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use PhpSpec\ObjectBehavior;

class CreateTransactionCommandHandlerSpec extends ObjectBehavior
{
    function let(TransactionRepositoryInterface $transactionRepository, TransactionFactoryInterface $transactionFactory)
    {
        $this->beConstructedWith($transactionRepository, $transactionFactory);
    }

    function it_is_create_transaction_command_handler()
    {
        $this->shouldHaveType(CreateTransactionCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_creates_ask_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
    ) {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        );

        $transactionFactory
            ->createAsk(
                $command->fromOperation,
                $command->toOperation,
                $command->adjustmentOperations,
                $command->concludedAt
            )
            ->willReturn($transaction)
            ->shouldBeCalledOnce();

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_bid_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
    ) {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::BID,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        );

        $transactionFactory
            ->createBid(
                $command->fromOperation,
                $command->toOperation,
                $command->adjustmentOperations,
                $command->concludedAt
            )
            ->willReturn($transaction)
            ->shouldBeCalledOnce();

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_deposit_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
    ) {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::DEPOSIT,
            null,
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        );

        $transactionFactory
            ->createDeposit(
                $command->toOperation,
                $command->adjustmentOperations,
                $command->concludedAt
            )
            ->willReturn($transaction)
            ->shouldBeCalledOnce();

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_withdraw_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        OperationInterface $fromOperation,
        TransactionInterface $transaction,
    ) {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::WITHDRAW,
            $fromOperation->getWrappedObject(),
            null,
            [],
            new \DateTimeImmutable(),
        );

        $transactionFactory
            ->createWithdraw(
                $command->fromOperation,
                $command->adjustmentOperations,
                $command->concludedAt
            )
            ->willReturn($transaction)
            ->shouldBeCalledOnce();

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_fee_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        TransactionInterface $transaction,
    ) {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::FEE,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $transactionFactory
            ->createFee(
                $command->adjustmentOperations,
                $command->concludedAt
            )
            ->willReturn($transaction)
            ->shouldBeCalledOnce();

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $this($command);
    }

    function it_requires_operations_for_ask_transaction()
    {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [$command]);
    }

    function it_requires_operations_for_bid_transaction()
    {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::BID,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [$command]);
    }

    function it_requires_operations_for_deposit_transaction()
    {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::DEPOSIT,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [$command]);
    }

    function it_requires_operations_for_withdraw_transaction()
    {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::WITHDRAW,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $this->shouldThrow(\InvalidArgumentException::class)->during('__invoke', [$command]);
    }

    function it_does_not_require_operations_for_fee_transaction()
    {
        $command = new CreateTransactionCommand(
            TransactionTypeEnum::FEE,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        );

        $this->shouldNotThrow(\InvalidArgumentException::class)->during('__invoke', [$command]);
    }
}
