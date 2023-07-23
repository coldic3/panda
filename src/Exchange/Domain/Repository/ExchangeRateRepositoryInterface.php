<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Repository;

use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Symfony\Component\Uid\Uuid;

interface ExchangeRateRepositoryInterface extends RepositoryInterface
{
    public function save(ExchangeRateInterface $exchangeRate): void;

    public function remove(ExchangeRateInterface $exchangeRate): void;

    public function findById(Uuid $id): ?ExchangeRateInterface;

    public function findByBaseAndQuoteResources(string $baseResourceTicker, string $quoteResourceTicker): ?ExchangeRateInterface;

    public function withBaseAndQuoteResourcesExist(string $baseResourceTicker, string $quoteResourceTicker): bool;

    public function defaultQuery(string $baseResourceTicker = null, string $quoteResourceTicker = null): QueryInterface;
}
