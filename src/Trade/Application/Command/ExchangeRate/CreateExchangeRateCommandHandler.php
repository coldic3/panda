<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\ExchangeRate;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Domain\Events\ExchangeRateCreatedEvent;
use Panda\Trade\Domain\Events\ReversedExchangeRateCreatedEvent;
use Panda\Trade\Domain\Factory\ExchangeRateFactoryInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CreateExchangeRateCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private AssetRepositoryInterface $assetRepository,
        private ExchangeRateFactoryInterface $exchangeRateFactory,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateExchangeRateCommand|CreateReversedExchangeRateCommand $command): ExchangeRateInterface
    {
        Assert::notNull(
            $baseAsset = $this->assetRepository->findById($command->baseAssetId)
        );
        Assert::notNull(
            $quoteAsset = $this->assetRepository->findById($command->quoteAssetId)
        );

        $exchangeRate = $this->exchangeRateFactory->create($baseAsset, $quoteAsset, $command->rate);

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
