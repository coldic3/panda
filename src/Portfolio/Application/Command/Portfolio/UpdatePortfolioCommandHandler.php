<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class UpdatePortfolioCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(UpdatePortfolioCommand $command): PortfolioInterface
    {
        $portfolio = $this->portfolioRepository->findById($command->id);
        Assert::notNull($portfolio);

        $portfolio->setName($command->name ?? $portfolio->getName());

        $this->validator->validate($portfolio, ['groups' => ['panda:update']]);

        $this->portfolioRepository->save($portfolio);

        return $portfolio;
    }
}
