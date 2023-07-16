<?php

declare(strict_types=1);

namespace Panda\Trade\Application\EventListener;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Trade\Application\Command\ExchangeRate\CreateReversedExchangeRateCommand;
use Panda\Trade\Application\Command\ExchangeRate\UpdateReversedExchangeRateCommand;
use Panda\Trade\Domain\Events\ExchangeRateCreatedEvent;
use Panda\Trade\Domain\Events\ExchangeRateUpdatedEvent;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
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

        $reversedExchangeRate = $this->exchangeRateRepository->findByBaseAndQuoteAssets(
            $createdExchangeRate->getQuoteAsset(),
            $createdExchangeRate->getBaseAsset()
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
                $createdExchangeRate->getQuoteAsset()->getId(),
                $createdExchangeRate->getBaseAsset()->getId(),
                $reversedRate,
            )
        );
    }
}
