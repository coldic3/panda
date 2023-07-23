<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\EventListener;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRate\CreateReversedExchangeRateCommand;
use Panda\Exchange\Application\Command\ExchangeRate\UpdateReversedExchangeRateCommand;
use Panda\Exchange\Domain\Event\ExchangeRateCreatedEvent;
use Panda\Exchange\Domain\Event\ExchangeRateUpdatedEvent;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CreateOrUpdateReversedExchangeRateWhenExchangeRateChanged
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ExchangeRateCreatedEvent|ExchangeRateUpdatedEvent $event): void
    {
        Assert::notNull(
            $createdExchangeRate = $this->exchangeRateRepository->findById($event->exchangeRateId)
        );

        $reversedExchangeRate = $this->exchangeRateRepository->findByBaseAndQuoteResources(
            $createdExchangeRate->getQuoteTicker(),
            $createdExchangeRate->getBaseTicker(),
        );

        $reversedRate = round(1 / $createdExchangeRate->getRate(), ExchangeRateInterface::RATE_PRECISION);

        if (null !== $reversedExchangeRate) {
            $this->commandBus->dispatch(
                new UpdateReversedExchangeRateCommand($reversedExchangeRate->getId(), $reversedRate)
            );

            return;
        }

        $this->commandBus->dispatch(
            new CreateReversedExchangeRateCommand(
                $createdExchangeRate->getQuoteTicker(),
                $createdExchangeRate->getBaseTicker(),
                $reversedRate,
            )
        );
    }
}
