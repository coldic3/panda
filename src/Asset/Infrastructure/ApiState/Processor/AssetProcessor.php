<?php

declare(strict_types=1);

namespace Panda\Asset\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Asset\Application\Command\Asset\CreateAssetCommand;
use Panda\Asset\Application\Command\Asset\DeleteAssetCommand;
use Panda\Asset\Application\Command\Asset\UpdateAssetCommand;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Infrastructure\ApiResource\AssetResource;
use Panda\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final class AssetProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?AssetResource
    {
        Assert::isInstanceOf($data, AssetResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteAssetCommand($uriVariables['id']));

            return null;
        }

        $command = !isset($uriVariables['id'])
            ? new CreateAssetCommand((string) $data->ticker, (string) $data->name)
            : new UpdateAssetCommand($uriVariables['id'], $data->ticker, $data->name)
        ;

        /** @var AssetInterface $model */
        $model = $this->commandBus->dispatch($command);

        return AssetResource::fromModel($model);
    }
}
