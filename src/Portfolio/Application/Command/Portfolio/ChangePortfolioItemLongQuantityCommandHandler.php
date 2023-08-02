<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;

final readonly class ChangePortfolioItemLongQuantityCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioItemRepositoryInterface $portfolioItemRepository,
        private PortfolioResolverInterface $portfolioResolver,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(ChangePortfolioItemLongQuantityCommand $command): ?PortfolioItemInterface
    {
        $portfolio = $this->portfolioResolver->resolve();
        $portfolioItem = $this->portfolioItemRepository->findByTickerWithinPortfolio($command->ticker, $portfolio);

        if (null === $portfolioItem) {
            throw new PortfolioItemWithTickerNotFoundException($command->ticker, $portfolio->getId()->toRfc4122());
        }

        if ($command->quantityAdjustment > 0) {
            $portfolioItem->addLongQuantity($command->quantityAdjustment);
        } else {
            $portfolioItem->removeLongQuantity(-$command->quantityAdjustment);
        }

        $this->validator->validate($portfolioItem, ['groups' => ['panda:update']]);

        $this->portfolioItemRepository->save($portfolioItem);

        return $portfolioItem;
    }
}
