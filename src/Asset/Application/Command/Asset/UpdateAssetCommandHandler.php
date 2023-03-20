<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Command\Asset;

use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;
use Webmozart\Assert\Assert;

final class UpdateAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly AssetRepositoryInterface $assetRepository)
    {
    }

    public function __invoke(UpdateAssetCommand $command): AssetInterface
    {
        $asset = $this->assetRepository->findById($command->id);
        Assert::notNull($asset);

        $asset->setTicker($command->ticker ?? $asset->getTicker());
        $asset->setName($command->name ?? $asset->getName());

        $this->assetRepository->save($asset);

        return $asset;
    }
}
