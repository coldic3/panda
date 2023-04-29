<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\AntiCorruptionLayer\Application\Query\FindResourceQuery;
use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Panda\Trade\Domain\Factory\OperationFactoryInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use Panda\Trade\Infrastructure\ApiResource\OperationResource;
use Panda\Trade\Infrastructure\ApiResource\TransactionResource;
use Webmozart\Assert\Assert;

final class TransactionCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly OperationFactoryInterface $operationFactory,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var TransactionResource $data */
        Assert::isInstanceOf($data, TransactionResource::class);

        $fromOperation = $this->findOperation($data->fromOperation);
        $toOperation = $this->findOperation($data->toOperation);

        $adjustmentOperations = [];
        foreach ($data->adjustmentOperations ?? [] as $adjustmentOperation) {
            // FIXME [Performance] Avoid querying for resources in a loop. Use a single query instead.
            $adjustmentOperations[] = $this->findOperation($adjustmentOperation);
        }
        $adjustmentOperations = array_filter($adjustmentOperations);

        $command = new CreateTransactionCommand(
            $data->type ?? TransactionTypeEnum::ASK,
            $fromOperation,
            $toOperation,
            $adjustmentOperations,
            $data->concludedAt ?? new \DateTimeImmutable(),
        );

        /** @var TransactionInterface $model */
        $model = $this->commandBus->dispatch($command);

        return TransactionResource::fromModel($model);
    }

    private function findOperation(?OperationResource $operationResource): ?OperationInterface
    {
        if (null === $operationResource?->resource?->id) {
            return null;
        }

        Assert::isInstanceOf(
            $resource = $this->queryBus->ask(new FindResourceQuery($operationResource->resource->id)),
            ResourceInterface::class
        );

        return $this->operationFactory->create($resource, (int) $operationResource->quantity);
    }
}
