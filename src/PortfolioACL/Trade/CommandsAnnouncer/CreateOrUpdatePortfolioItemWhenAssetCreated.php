<?php

declare(strict_types=1);

namespace Panda\PortfolioACL\Trade\CommandsAnnouncer;

use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioItemCommand;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioItemCommand;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Trade\Application\Query\Asset\FindAssetQuery;
use Panda\Trade\Domain\Events\AssetCreatedEvent;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Webmozart\Assert\Assert;

final readonly class CreateOrUpdatePortfolioItemWhenAssetCreated
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(AssetCreatedEvent $event): void
    {
        Assert::isInstanceOf(
            $asset = $this->queryBus->ask(new FindAssetQuery($event->assetId)),
            AssetInterface::class,
        );

        try {
            $this->commandBus->dispatch(
                new CreatePortfolioItemCommand(
                    $asset->getTicker(),
                    $asset->getName(),
                ),
            );
        } catch (PortfolioItemWithTickerNotFoundException) {
            $this->commandBus->dispatch(
                new UpdatePortfolioItemCommand(
                    $asset->getTicker(),
                    $asset->getTicker(),
                    $asset->getName(),
                ),
            );
        }
    }
}
