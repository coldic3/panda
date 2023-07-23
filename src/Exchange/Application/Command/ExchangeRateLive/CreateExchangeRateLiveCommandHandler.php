<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Exchange\Domain\Event\ExchangeRateLiveCreatedEvent;
use Panda\Exchange\Domain\Event\ReversedExchangeLiveRateCreatedEvent;
use Panda\Exchange\Domain\Factory\ExchangeRateLiveFactoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;

final readonly class CreateExchangeRateLiveCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateLiveRepositoryInterface $exchangeRateRepository,
        private ExchangeRateLiveFactoryInterface $exchangeRateFactory,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateExchangeRateLiveCommand|CreateReversedExchangeRateLiveCommand $command): ExchangeRateLiveInterface
    {
        $exchangeRate = $this->exchangeRateFactory->create($command->baseTicker, $command->quoteTicker, $command->rate);

        $this->validator->validate($exchangeRate, ['groups' => ['panda:create']]);

        $this->exchangeRateRepository->save($exchangeRate);

        switch (get_class($command)) {
            case CreateExchangeRateLiveCommand::class:
                $this->eventBus->dispatch(new ExchangeRateLiveCreatedEvent($exchangeRate->getId()));
                break;
            case CreateReversedExchangeRateLiveCommand::class:
                $this->eventBus->dispatch(new ReversedExchangeLiveRateCreatedEvent($exchangeRate->getId()));
                break;
        }

        return $exchangeRate;
    }
}
