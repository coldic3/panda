<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

final readonly class CreateAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private AssetFactoryInterface $assetFactory,
    ) {
    }

    public function __invoke(CreateAssetCommand $command): AssetInterface
    {
        $asset = $this->assetFactory->create($command->ticker, $command->name);

        $this->assetRepository->save($asset);

        return $asset;
    }
}
