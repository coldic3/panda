<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

final readonly class CreatePortfolioCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private PortfolioFactoryInterface $portfolioFactory
    ) {
    }

    public function __invoke(CreatePortfolioCommand $command): PortfolioInterface
    {
        $portfolio = $this->portfolioFactory->create(
            $command->name,
            !$this->portfolioRepository->defaultExists()
        );

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
