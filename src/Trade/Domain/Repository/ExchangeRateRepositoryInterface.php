<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Repository;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Symfony\Component\Uid\Uuid;

interface ExchangeRateRepositoryInterface extends RepositoryInterface
{
    public function save(ExchangeRateInterface $exchangeRate): void;

    public function remove(ExchangeRateInterface $exchangeRate): void;

    public function findById(Uuid $id): ?ExchangeRateInterface;

    public function findByBaseAndQuoteAssets(AssetInterface $baseAsset, AssetInterface $quoteAsset): ?ExchangeRateInterface;

    public function withBaseAndQuoteAssetsExists(AssetInterface $baseAsset, AssetInterface $quoteAsset): bool;

    public function defaultQuery(OwnerInterface $owner, ?string $ticker = null): QueryInterface;
}
