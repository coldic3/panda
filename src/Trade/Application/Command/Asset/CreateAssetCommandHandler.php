<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

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
