<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\EventListener;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLive\CreateReversedExchangeRateLiveCommand;
use Panda\Exchange\Application\Command\ExchangeRateLive\UpdateReversedExchangeRateLiveCommand;
use Panda\Exchange\Domain\Event\ExchangeRateLiveCreatedEvent;
use Panda\Exchange\Domain\Event\ExchangeRateLiveUpdatedEvent;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CreateOrUpdateReversedExchangeRateLiveWhenExchangeRateLiveChanged
{
    public function __construct(
        private ExchangeRateLiveRepositoryInterface $exchangeRateRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ExchangeRateLiveCreatedEvent|ExchangeRateLiveUpdatedEvent $event): void
    {
        Assert::notNull(
            $createdExchangeRate = $this->exchangeRateRepository->findById($event->exchangeRateLiveId)
        );

        $reversedExchangeRate = $this->exchangeRateRepository->findByBaseAndQuoteResources(
            $createdExchangeRate->getQuoteTicker(),
            $createdExchangeRate->getBaseTicker(),
        );

        $reversedRate = round(1 / $createdExchangeRate->getRate(), ExchangeRateInterface::RATE_PRECISION);

        if (null !== $reversedExchangeRate) {
            $this->commandBus->dispatch(
                new UpdateReversedExchangeRateLiveCommand($reversedExchangeRate->getId(), $reversedRate)
            );

            return;
        }

        $this->commandBus->dispatch(
            new CreateReversedExchangeRateLiveCommand(
                $createdExchangeRate->getQuoteTicker(),
                $createdExchangeRate->getBaseTicker(),
                $reversedRate,
            )
        );
    }
}
