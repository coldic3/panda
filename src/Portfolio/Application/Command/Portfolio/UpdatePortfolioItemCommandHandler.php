<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\Portfolio\Domain\ValueObject\Resource;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;

final readonly class UpdatePortfolioItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioItemRepositoryInterface $portfolioItemRepository,
        private PortfolioResolverInterface $portfolioResolver,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdatePortfolioItemCommand $command): PortfolioItemInterface
    {
        $portfolio = $this->portfolioResolver->resolve();
        $portfolioItem = $this->portfolioItemRepository->findByTickerWithinPortfolio(
            $command->previousTicker,
            $portfolio,
        );

        if (null === $portfolioItem) {
            throw new PortfolioItemWithTickerNotFoundException($command->previousTicker, $portfolio->getId()->toRfc4122());
        }

        $portfolioItem->setResource(new Resource($command->ticker, $command->name));

        $this->validator->validate($portfolioItem, ['groups' => ['panda:update']]);

        $this->portfolioItemRepository->save($portfolioItem);

        return $portfolioItem;
    }
}
