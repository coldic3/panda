<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class ChangeDefaultPortfolioCommandHandler implements CommandHandlerInterface
{
    public function __construct(private PortfolioRepositoryInterface $portfolioRepository)
    {
    }

    public function __invoke(ChangeDefaultPortfolioCommand $command): PortfolioInterface
    {
        $portfolio = $this->portfolioRepository->findById($command->id);
        Assert::notNull($portfolio);

        $this->portfolioRepository->findDefault()?->setDefault(false);
        $portfolio->setDefault(true);

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
