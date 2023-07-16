<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\ExchangeRate;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Domain\Events\ExchangeRateUpdatedEvent;
use Panda\Trade\Domain\Events\ReversedExchangeRateUpdatedEvent;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class UpdateExchangeRateCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdateExchangeRateCommand|UpdateReversedExchangeRateCommand $command): ExchangeRateInterface
    {
        $exchangeRate = $this->exchangeRateRepository->findById($command->id);
        Assert::notNull($exchangeRate);

        $exchangeRate->setRate($command->rate ?? $exchangeRate->getRate());

        $this->validator->validate($exchangeRate, ['groups' => ['panda:update']]);

        $this->exchangeRateRepository->save($exchangeRate);

        switch (get_class($command)) {
            case UpdateExchangeRateCommand::class:
                $this->eventBus->dispatch(new ExchangeRateUpdatedEvent($exchangeRate->getId()));
                break;
            case UpdateReversedExchangeRateCommand::class:
                $this->eventBus->dispatch(new ReversedExchangeRateUpdatedEvent($exchangeRate->getId()));
                break;
        }

        return $exchangeRate;
    }
}
