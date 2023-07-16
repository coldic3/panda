<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\ExchangeRate;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class FindExchangeRateByAssetIdsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private AssetRepositoryInterface $assetRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindExchangeRateByAssetIdsQuery $query): ?ExchangeRateInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();

        Assert::notNull(
            $baseAsset = $this->assetRepository->findById($query->baseAssetId)
        );
        Assert::notNull(
            $quoteAsset = $this->assetRepository->findById($query->quoteAssetId)
        );

        $exchangeRate = $this->exchangeRateRepository->findByBaseAndQuoteAssets($baseAsset, $quoteAsset);

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
