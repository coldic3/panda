<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Query\ExchangeRate;

use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class FindExchangeRateQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(FindExchangeRateQuery $query): ?ExchangeRateInterface
    {
        return $this->exchangeRateRepository->findById($query->id);
    }
}
