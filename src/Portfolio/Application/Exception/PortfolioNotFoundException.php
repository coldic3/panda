<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Exception;

final class PortfolioNotFoundException extends \Exception
{
    public function __construct(?string $portfolioId = null)
    {
        parent::__construct(
            null === $portfolioId
                ? 'Default portfolio not found.'
                : sprintf('Portfolio with ID "%s" not found.', $portfolioId)
        );
    }
}
