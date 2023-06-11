<?php

namespace spec\Panda\Trade\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Shared\Application\Event\EventBusInterface;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommandHandler;
use Panda\Trade\Domain\Events\TransactionCreatedEvent;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class CreateTransactionCommandHandlerSpec extends ObjectBehavior
{
    function let(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
    ) {
        $this->beConstructedWith($transactionRepository, $transactionFactory, $eventBus);
    }

    function it_is_create_transaction_command_handler()
    {
        $this->shouldHaveType(CreateTransactionCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_creates_ask_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
        Uuid $transactionId,
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

        $transaction->getId()->willReturn($transactionId);

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $eventBus->dispatch(new TransactionCreatedEvent($transactionId->getWrappedObject()))->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_bid_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
        Uuid $transactionId,
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

        $transaction->getId()->willReturn($transactionId);

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $eventBus->dispatch(new TransactionCreatedEvent($transactionId->getWrappedObject()))->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_deposit_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
        OperationInterface $toOperation,
        TransactionInterface $transaction,
        Uuid $transactionId,
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

        $transaction->getId()->willReturn($transactionId);

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $eventBus->dispatch(new TransactionCreatedEvent($transactionId->getWrappedObject()))->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_withdraw_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
        OperationInterface $fromOperation,
        TransactionInterface $transaction,
        Uuid $transactionId,
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

        $transaction->getId()->willReturn($transactionId);

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $eventBus->dispatch(new TransactionCreatedEvent($transactionId->getWrappedObject()))->shouldBeCalledOnce();

        $this($command);
    }

    function it_creates_fee_transaction(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        EventBusInterface $eventBus,
        TransactionInterface $transaction,
        Uuid $transactionId,
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

        $transaction->getId()->willReturn($transactionId);

        $transactionRepository->save($transaction)->shouldBeCalledOnce();

        $eventBus->dispatch(new TransactionCreatedEvent($transactionId->getWrappedObject()))->shouldBeCalledOnce();

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
