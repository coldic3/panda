<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLive\CreateExchangeRateLiveCommand;
use Panda\Exchange\Application\Command\ExchangeRateLive\DeleteExchangeRateLiveCommand;
use Panda\Exchange\Application\Command\ExchangeRateLive\UpdateExchangeRateLiveCommand;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateLiveResource;
use Webmozart\Assert\Assert;

final readonly class ExchangeRateLiveProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?ExchangeRateLiveResource
    {
        /** @var ExchangeRateLiveResource $data */
        Assert::isInstanceOf($data, ExchangeRateLiveResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteExchangeRateLiveCommand($uriVariables['id']));

            return null;
        }

        $command = !isset($uriVariables['id'])
            ? new CreateExchangeRateLiveCommand(
                (string) $data->baseTicker,
                (string) $data->quoteTicker,
                (float) $data->rate,
            )
            : new UpdateExchangeRateLiveCommand($uriVariables['id'], (float) $data->rate);

        /** @var ExchangeRateLiveInterface $model */
        $model = $this->commandBus->dispatch($command);

        return ExchangeRateLiveResource::fromModel($model);
    }
}
