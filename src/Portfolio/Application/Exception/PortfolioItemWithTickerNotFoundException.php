<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Exception;

final class PortfolioItemWithTickerNotFoundException extends \Exception
{
    public function __construct(string $ticker, string $portfolioId)
    {
        parent::__construct(sprintf(
            'Portfolio item with ticker "%s" not found for a portfolio ID "%s".',
            $ticker,
            $portfolioId,
        ));
    }
}
