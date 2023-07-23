<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRate;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Exchange\Domain\Event\ExchangeRateCreatedEvent;
use Panda\Exchange\Domain\Event\ReversedExchangeRateCreatedEvent;
use Panda\Exchange\Domain\Factory\ExchangeRateFactoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class CreateExchangeRateCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private ExchangeRateFactoryInterface $exchangeRateFactory,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateExchangeRateCommand|CreateReversedExchangeRateCommand $command): ExchangeRateInterface
    {
        $exchangeRate = $this->exchangeRateFactory->create($command->baseTicker, $command->quoteTicker, $command->rate);

        $this->validator->validate($exchangeRate, ['groups' => ['panda:create']]);

        $this->exchangeRateRepository->save($exchangeRate);

        switch (get_class($command)) {
            case CreateExchangeRateCommand::class:
                $this->eventBus->dispatch(new ExchangeRateCreatedEvent($exchangeRate->getId()));
                break;
            case CreateReversedExchangeRateCommand::class:
                $this->eventBus->dispatch(new ReversedExchangeRateCreatedEvent($exchangeRate->getId()));
                break;
        }

        return $exchangeRate;
    }
}
