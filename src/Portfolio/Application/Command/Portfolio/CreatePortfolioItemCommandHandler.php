<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Exception\DefaultPortfolioNotFoundException;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class CreatePortfolioItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private PortfolioItemFactoryInterface $portfolioItemFactory,
    ) {
    }

    public function __invoke(CreatePortfolioItemCommand $command): PortfolioItemInterface
    {
        if (null === $portfolio = $this->portfolioRepository->findDefault()) {
            throw new DefaultPortfolioNotFoundException();
        }

        $portfolioItem = $this->portfolioItemFactory->create($command->ticker, $command->name, $portfolio);

        $this->portfolioRepository->save($portfolio);

        return $portfolioItem;
    }
}