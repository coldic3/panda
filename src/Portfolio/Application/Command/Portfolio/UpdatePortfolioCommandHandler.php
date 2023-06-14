<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;
use Webmozart\Assert\Assert;

final readonly class UpdatePortfolioCommandHandler implements CommandHandlerInterface
{
    public function __construct(private PortfolioRepositoryInterface $portfolioRepository)
    {
    }

    public function __invoke(UpdatePortfolioCommand $command): PortfolioInterface
    {
        $portfolio = $this->portfolioRepository->findById($command->id);
        Assert::notNull($portfolio);

        $portfolio->setName($command->name ?? $portfolio->getName());

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}