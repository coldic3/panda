<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Trade\Application\Command\Asset\ChangeAssetTickerCommand;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Infrastructure\ApiResource\AssetResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class AssetChangeTickerProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?AssetResource
    {
        /** @var AssetResource $data */
        Assert::isInstanceOf($data, AssetResource::class);
        Assert::notNull($data->ticker);
        Assert::isInstanceOf($id = $uriVariables['id'] ?? null, Uuid::class);

        $command = new ChangeAssetTickerCommand($id, $data->ticker);

        /** @var AssetInterface $model */
        $model = $this->commandBus->dispatch($command);

        return AssetResource::fromModel($model);
    }
}
