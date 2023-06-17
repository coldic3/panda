<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;

final readonly class CreatePortfolioCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private PortfolioFactoryInterface $portfolioFactory,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreatePortfolioCommand $command): PortfolioInterface
    {
        $portfolio = $this->portfolioFactory->create(
            $command->name,
            !$this->portfolioRepository->defaultExists()
        );

        $this->validator->validate($portfolio, ['groups' => ['panda:create']]);

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
