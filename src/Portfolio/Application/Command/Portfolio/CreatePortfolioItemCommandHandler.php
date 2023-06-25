<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Resolver\PortfolioResolverInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;

final readonly class CreatePortfolioItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioItemRepositoryInterface $portfolioItemRepository,
        private PortfolioItemFactoryInterface $portfolioItemFactory,
        private PortfolioResolverInterface $portfolioResolver,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreatePortfolioItemCommand $command): PortfolioItemInterface
    {
        $portfolio = $this->portfolioResolver->resolve();
        $portfolioItem = $this->portfolioItemFactory->create($command->ticker, $command->name, $portfolio);

        $this->validator->validate($portfolioItem, ['groups' => ['panda:create']]);

        $this->portfolioItemRepository->save($portfolioItem);

        return $portfolioItem;
    }
}
