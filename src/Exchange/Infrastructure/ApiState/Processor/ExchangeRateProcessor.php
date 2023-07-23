<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRate\CreateExchangeRateCommand;
use Panda\Exchange\Application\Command\ExchangeRate\DeleteExchangeRateCommand;
use Panda\Exchange\Application\Command\ExchangeRate\UpdateExchangeRateCommand;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateResource;
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
                (string) $data->baseResourceTicker,
                (string) $data->quoteResourceTicker,
                (float) $data->rate,
            )
            : new UpdateExchangeRateCommand($uriVariables['id'], (float) $data->rate);

        /** @var ExchangeRateInterface $model */
        $model = $this->commandBus->dispatch($command);

        return ExchangeRateResource::fromModel($model);
    }
}
