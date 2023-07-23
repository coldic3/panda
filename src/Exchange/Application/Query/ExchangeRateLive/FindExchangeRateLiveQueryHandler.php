<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRateLive;

use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;

final readonly class FindExchangeRateLiveQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExchangeRateLiveRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(FindExchangeRateLiveQuery $query): ?ExchangeRateLiveInterface
    {
        return $this->exchangeRateRepository->findById($query->id);
    }
}
