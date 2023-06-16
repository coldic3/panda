<?php

declare(strict_types=1);

namespace Panda\PortfolioACL\Trade\CommandsAnnouncer;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioItemCommand;
use Panda\Trade\Application\Query\Asset\FindAssetQuery;
use Panda\Trade\Domain\Events\AssetUpdatedEvent;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Webmozart\Assert\Assert;

final readonly class UpdatePortfolioItemWhenAssetUpdated
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(AssetUpdatedEvent $event): void
    {
        Assert::isInstanceOf(
            $asset = $this->queryBus->ask(new FindAssetQuery($event->assetId)),
            AssetInterface::class,
        );

        $this->commandBus->dispatch(
            new UpdatePortfolioItemCommand(
                $asset->getTicker(),
                $asset->getName(),
            ),
        );
    }
}
