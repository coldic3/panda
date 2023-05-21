<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Trade\Application\Command\Asset\CreateAssetCommand;
use Panda\Trade\Application\Command\Asset\DeleteAssetCommand;
use Panda\Trade\Application\Command\Asset\UpdateAssetCommand;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Infrastructure\ApiResource\AssetResource;
use Webmozart\Assert\Assert;

final readonly class AssetProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
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
