<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use Panda\Shared\Application\Command\CommandHandlerInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

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
