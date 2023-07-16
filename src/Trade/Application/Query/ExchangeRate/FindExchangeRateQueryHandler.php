<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\ExchangeRate;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class FindExchangeRateQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindExchangeRateQuery $query): ?ExchangeRateInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $exchangeRate = $this->exchangeRateRepository->findById($query->id);

        if (null === $exchangeRate) {
            return null;
        }

        $owner = $exchangeRate->getBaseAsset()->getOwnedBy();

        if (null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $exchangeRate;
    }
}
