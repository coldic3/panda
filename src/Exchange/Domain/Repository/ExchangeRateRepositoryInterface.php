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

    public function findByBaseAndQuoteResources(string $baseTicker, string $quoteTicker): ?ExchangeRateInterface;

    public function withBaseAndQuoteResourcesExist(string $baseTicker, string $quoteTicker): bool;

    public function defaultQuery(string $baseTicker = null, string $quoteTicker = null): QueryInterface;
}
