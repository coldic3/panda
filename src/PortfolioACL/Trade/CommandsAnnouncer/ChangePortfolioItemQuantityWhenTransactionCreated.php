<?php

declare(strict_types=1);

namespace Panda\PortfolioACL\Trade\CommandsAnnouncer;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommand;
use Panda\Trade\Application\Query\Transaction\FindTransactionQuery;
use Panda\Trade\Domain\Event\TransactionCreatedEvent;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Webmozart\Assert\Assert;

final readonly class ChangePortfolioItemQuantityWhenTransactionCreated
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(TransactionCreatedEvent $event): void
    {
        Assert::isInstanceOf(
            $transaction = $this->queryBus->ask(new FindTransactionQuery($event->transactionId)),
            TransactionInterface::class,
        );

        $fromOperation = $transaction->getFromOperation();
        $toOperation = $transaction->getToOperation();
        $adjustmentOperations = $transaction->getAdjustmentOperations();

        if (null !== $fromOperation) {
            $this->commandBus->dispatch(
                new ChangePortfolioItemLongQuantityCommand(
                    $fromOperation->getAsset()->getTicker(),
                    -$fromOperation->getQuantity(),
                ),
            );
        }

        if (null !== $toOperation) {
            $this->commandBus->dispatch(
                new ChangePortfolioItemLongQuantityCommand(
                    $toOperation->getAsset()->getTicker(),
                    $toOperation->getQuantity(),
                ),
            );
        }

        foreach ($adjustmentOperations as $adjustmentOperation) {
            $this->commandBus->dispatch(
                new ChangePortfolioItemLongQuantityCommand(
                    $adjustmentOperation->getAsset()->getTicker(),
                    -$adjustmentOperation->getQuantity(),
                ),
            );
        }
    }
}
