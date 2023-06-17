<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Domain\Events\AssetUpdatedEvent;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class UpdateAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdateAssetCommand $command): AssetInterface
    {
        $asset = $this->assetRepository->findById($command->id);
        Assert::notNull($asset);

        $asset->setTicker($command->ticker ?? $asset->getTicker());
        $asset->setName($command->name ?? $asset->getName());

        $this->validator->validate($asset, ['groups' => ['panda:update']]);

        $this->assetRepository->save($asset);

        $this->eventBus->dispatch(new AssetUpdatedEvent($asset->getId()));

        return $asset;
    }
}
