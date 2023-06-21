<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Domain\Events\AssetTickerChangedEvent;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class ChangeAssetTickerCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(ChangeAssetTickerCommand $command): AssetInterface
    {
        $asset = $this->assetRepository->findById($command->id);
        Assert::notNull($asset);

        $previousTicker = $asset->getTicker();
        $newTicker = $command->ticker;

        $asset->setTicker($newTicker);

        $this->validator->validate($asset, ['groups' => ['panda:update']]);

        $this->assetRepository->save($asset);

        $this->eventBus->dispatch(new AssetTickerChangedEvent($asset->getId(), $previousTicker, $newTicker));

        return $asset;
    }
}
