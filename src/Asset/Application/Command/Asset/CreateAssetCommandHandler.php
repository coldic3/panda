<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Command\Asset;

use Panda\Asset\Domain\Factory\AssetFactoryInterface;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

final class CreateAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly AssetFactoryInterface $assetFactory,
    ) {
    }

    public function __invoke(CreateAssetCommand $command): AssetInterface
    {
        $asset = $this->assetFactory->create($command->ticker, $command->name);

        $this->assetRepository->save($asset);

        return $asset;
    }
}
