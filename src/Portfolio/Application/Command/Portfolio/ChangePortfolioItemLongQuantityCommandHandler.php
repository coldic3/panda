<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class ChangePortfolioItemLongQuantityCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private PortfolioItemFactoryInterface $portfolioItemFactory,
    ) {
    }

    public function __invoke(ChangePortfolioItemLongQuantityCommand $command): ?PortfolioInterface
    {
        $portfolio = $this->portfolioRepository->findDefault();

        if (null === $portfolio) {
            return null;
        }

        // FIXME [Performance] This is not optimal, we should use a query to get the portfolio item.
        $portfolioItem = $portfolio->getItems()->filter(
            fn (PortfolioItemInterface $item) => $item->getResource()->getTicker() === $command->ticker,
        )->first();

        if (false === $portfolioItem) {
            $portfolioItem = $this->portfolioItemFactory->create($command->ticker, $command->ticker, $portfolio);
        }

        if ($command->quantity > 0) {
            $portfolioItem->addLongQuantity($command->quantity);
        } else {
            $portfolioItem->removeLongQuantity(-$command->quantity);
        }

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
