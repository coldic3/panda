<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Exchange\Domain\Event\ExchangeRateLiveUpdatedEvent;
use Panda\Exchange\Domain\Event\ReversedExchangeRateLiveUpdatedEvent;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class UpdateExchangeRateLiveCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateLiveRepositoryInterface $exchangeRateRepository,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdateExchangeRateLiveCommand|UpdateReversedExchangeRateLiveCommand $command): ExchangeRateLiveInterface
    {
        $exchangeRate = $this->exchangeRateRepository->findById($command->id);
        Assert::notNull($exchangeRate);

        $exchangeRate->setRate($command->rate ?? $exchangeRate->getRate());

        $this->validator->validate($exchangeRate, ['groups' => ['panda:update']]);

        $this->exchangeRateRepository->save($exchangeRate);

        switch (get_class($command)) {
            case UpdateExchangeRateLiveCommand::class:
                $this->eventBus->dispatch(new ExchangeRateLiveUpdatedEvent($exchangeRate->getId()));
                break;
            case UpdateReversedExchangeRateLiveCommand::class:
                $this->eventBus->dispatch(new ReversedExchangeRateLiveUpdatedEvent($exchangeRate->getId()));
                break;
        }

        return $exchangeRate;
    }
}
