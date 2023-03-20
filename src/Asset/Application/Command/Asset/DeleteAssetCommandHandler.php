<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Command\Asset;

use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

final class DeleteAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly AssetRepositoryInterface $assetRepository)
    {
    }

    public function __invoke(DeleteAssetCommand $command): void
    {
        $asset = $this->assetRepository->findById($command->id);

        if (null === $asset) {
            return;
        }

        $this->assetRepository->remove($asset);
    }
}
