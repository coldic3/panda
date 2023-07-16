<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Trade\Application\Command\ExchangeRate\CreateExchangeRateCommand;
use Panda\Trade\Application\Command\ExchangeRate\DeleteExchangeRateCommand;
use Panda\Trade\Application\Command\ExchangeRate\UpdateExchangeRateCommand;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Infrastructure\ApiResource\ExchangeRateResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ExchangeRateProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?ExchangeRateResource
    {
        /** @var ExchangeRateResource $data */
        Assert::isInstanceOf($data, ExchangeRateResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteExchangeRateCommand($uriVariables['id']));

            return null;
        }

        $command = !isset($uriVariables['id'])
            ? new CreateExchangeRateCommand(
                $data->baseAsset?->id ?? Uuid::v4(),
                $data->quoteAsset?->id ?? Uuid::v4(),
                (float) $data->rate
            )
            : new UpdateExchangeRateCommand($uriVariables['id'], (float) $data->rate);

        /** @var ExchangeRateInterface $model */
        $model = $this->commandBus->dispatch($command);

        return ExchangeRateResource::fromModel($model);
    }
}
