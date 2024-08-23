<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLog\CreateExchangeRateLogCommand;
use Panda\Exchange\Application\Command\ExchangeRateLog\DeleteExchangeRateLogCommand;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateLogResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ExchangeRateLogProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?ExchangeRateLogResource
    {
        /** @var ExchangeRateLogResource $data */
        Assert::isInstanceOf($data, ExchangeRateLogResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            Assert::isInstanceOf($id = $uriVariables['id'] ?? null, Uuid::class);

            $this->commandBus->dispatch(new DeleteExchangeRateLogCommand($id));

            return null;
        }

        $command = new CreateExchangeRateLogCommand(
            (string) $data->baseTicker,
            (string) $data->quoteTicker,
            (float) $data->rate,
            $data->startedAt ?? new \DateTimeImmutable('today midnight'),
            $data->endedAt ?? new \DateTimeImmutable('tomorrow midnight -1 second')
        );

        /** @var ExchangeRateLogInterface $model */
        $model = $this->commandBus->dispatch($command);

        return ExchangeRateLogResource::fromModel($model);
    }
}
