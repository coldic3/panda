<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Application\Exception\PortfolioNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

final readonly class PortfolioHttpResolver implements PortfolioResolverInterface
{
    private const PORTFOLIO_ID_HEADER = 'X-Portfolio-Id';

    public function __construct(
        private RequestStack $requestStack,
        private PortfolioRepositoryInterface $portfolioRepository,
    ) {
    }

    public function resolve(): PortfolioInterface
    {
        $request = $this->requestStack->getMainRequest();

        $portfolioId = $request?->headers?->get(self::PORTFOLIO_ID_HEADER);

        if (null !== $portfolioId && !Uuid::isValid($portfolioId)) {
            throw new PortfolioNotFoundException($portfolioId);
        }

        $portfolio = null === $portfolioId
            ? $this->portfolioRepository->findDefault()
            : $this->portfolioRepository->findById(Uuid::fromString($portfolioId));

        if (null === $portfolio) {
            throw new PortfolioNotFoundException($portfolioId);
        }

        return $portfolio;
    }
}
