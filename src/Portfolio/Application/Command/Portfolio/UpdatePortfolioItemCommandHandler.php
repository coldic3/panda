<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Exception\DefaultPortfolioNotFoundException;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Domain\ValueObject\Resource;

final readonly class UpdatePortfolioItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private PortfolioItemRepositoryInterface $portfolioItemRepository,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdatePortfolioItemCommand $command): PortfolioItemInterface
    {
        if (null === $portfolio = $this->portfolioRepository->findDefault()) {
            throw new DefaultPortfolioNotFoundException();
        }

        $portfolioItem = $this->portfolioItemRepository->findByTickerWithinPortfolio(
            $command->previousTicker,
            $portfolio,
        );

        if (null === $portfolioItem) {
            throw new PortfolioItemWithTickerNotFoundException($command->previousTicker, $portfolio->getId()->toRfc4122());
        }

        $portfolioItem->setResource(new Resource($command->ticker, $command->name));

        $this->validator->validate($portfolio, ['groups' => ['panda:update']]);

        $this->portfolioRepository->save($portfolio);

        return $portfolioItem;
    }
}
