<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Asset;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Core\Application\Event\EventBusInterface;
use Panda\Trade\Domain\Event\AssetCreatedEvent;
use Panda\Trade\Domain\Factory\AssetFactoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

final readonly class CreateAssetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private AssetFactoryInterface $assetFactory,
        private EventBusInterface $eventBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateAssetCommand $command): AssetInterface
    {
        $asset = $this->assetFactory->create($command->ticker, $command->name);

        $this->validator->validate($asset, ['groups' => ['panda:create']]);

        $this->assetRepository->save($asset);

        $this->eventBus->dispatch(new AssetCreatedEvent($asset->getId()));

        return $asset;
    }
}
