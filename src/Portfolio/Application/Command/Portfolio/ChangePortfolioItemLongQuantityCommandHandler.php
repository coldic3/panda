<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Exception\DefaultPortfolioNotFoundException;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class ChangePortfolioItemLongQuantityCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(ChangePortfolioItemLongQuantityCommand $command): ?PortfolioInterface
    {
        if (null === $portfolio = $this->portfolioRepository->findDefault()) {
            throw new DefaultPortfolioNotFoundException();
        }

        // FIXME [Performance] This is not optimal, we should use a query to get the portfolio item.
        $portfolioItem = $portfolio->getItems()->filter(
            fn (PortfolioItemInterface $item) => $item->getResource()->getTicker() === $command->ticker,
        )->first();

        if (false === $portfolioItem) {
            throw new PortfolioItemWithTickerNotFoundException($command->ticker);
        }

        if ($command->quantityAdjustment > 0) {
            $portfolioItem->addLongQuantity($command->quantityAdjustment);
        } else {
            $portfolioItem->removeLongQuantity(-$command->quantityAdjustment);
        }

        $this->validator->validate($portfolio, ['groups' => ['panda:update']]);

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
