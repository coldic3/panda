<?php

declare(strict_types=1);

namespace Panda\PortfolioACL\Trade\CommandsAnnouncer;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioItemCommand;
use Panda\Trade\Application\Query\Asset\FindAssetQuery;
use Panda\Trade\Domain\Events\AssetTickerChangedEvent;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Webmozart\Assert\Assert;

final readonly class UpdatePortfolioItemWhenAssetTickerChanged
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(AssetTickerChangedEvent $event): void
    {
        Assert::isInstanceOf(
            $asset = $this->queryBus->ask(new FindAssetQuery($event->assetId)),
            AssetInterface::class,
        );

        $this->commandBus->dispatch(
            new UpdatePortfolioItemCommand(
                $event->previousTicker,
                $asset->getTicker(),
                $asset->getName(),
            ),
        );
    }
}
