<?php

declare(strict_types=1);

namespace Panda\TradeACL\Exchange\IntegrityChecker;

use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Exchange\Application\Exception\ExchangeRateLogForBaseQuoteAndDatetimeNotFoundException;
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;
use Panda\Trade\Application\Query\Transaction\FindTransactionQuery;
use Panda\Trade\Domain\Event\TransactionCreatedEvent;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Webmozart\Assert\Assert;

final readonly class CheckCorrespondingExchangeRateLogsExistWhenTransactionCreated
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private PortfolioResolverInterface $portfolioResolver,
    ) {
    }

    public function __invoke(TransactionCreatedEvent $event): void
    {
        $portfolio = $this->portfolioResolver->resolve();

        Assert::isInstanceOf(
            /** @var TransactionInterface $transaction */
            $transaction = $this->queryBus->ask(new FindTransactionQuery($event->transactionId)),
            TransactionInterface::class,
        );

        $baseTicker = $portfolio->getMainResource()->getTicker();

        if (
            null !== $transaction->getFromOperation()
            && ($quoteTicker = $transaction->getFromOperation()->getAsset()->getTicker()) !== $baseTicker
        ) {
            $exchangeRateLog = $this->queryBus->ask(new FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery(
                $baseTicker,
                $quoteTicker,
                $transaction->getConcludedAt(),
            ));

            if (null === $exchangeRateLog) {
                throw new ExchangeRateLogForBaseQuoteAndDatetimeNotFoundException($baseTicker, $quoteTicker, $transaction->getConcludedAt());
            }
        }

        if (
            null !== $transaction->getToOperation()
            && ($quoteTicker = $transaction->getToOperation()->getAsset()->getTicker()) !== $baseTicker
        ) {
            $exchangeRateLog = $this->queryBus->ask(new FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery(
                $baseTicker,
                $quoteTicker,
                $transaction->getConcludedAt(),
            ));

            if (null === $exchangeRateLog) {
                throw new ExchangeRateLogForBaseQuoteAndDatetimeNotFoundException($baseTicker, $quoteTicker, $transaction->getConcludedAt());
            }
        }

        foreach ($transaction->getAdjustmentOperations() as $adjustmentOperation) {
            $quoteTicker = $adjustmentOperation->getAsset()->getTicker();

            if ($quoteTicker === $baseTicker) {
                continue;
            }

            $exchangeRateLog = $this->queryBus->ask(new FindExchangeRateLogByBaseQuoteTickersAndDatetimeQuery(
                $baseTicker,
                $quoteTicker,
                $transaction->getConcludedAt(),
            ));

            if (null === $exchangeRateLog) {
                throw new ExchangeRateLogForBaseQuoteAndDatetimeNotFoundException($baseTicker, $quoteTicker, $transaction->getConcludedAt());
            }
        }
    }
}
