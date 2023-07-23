<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Repository;

use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Symfony\Component\Uid\Uuid;

interface ExchangeRateLiveRepositoryInterface extends RepositoryInterface
{
    public function save(ExchangeRateLiveInterface $exchangeRate): void;

    public function remove(ExchangeRateLiveInterface $exchangeRate): void;

    public function findById(Uuid $id): ?ExchangeRateLiveInterface;

    public function findByBaseAndQuoteResources(string $baseTicker, string $quoteTicker): ?ExchangeRateLiveInterface;

    public function withBaseAndQuoteResourcesExist(string $baseTicker, string $quoteTicker): bool;

    public function defaultQuery(string $baseTicker = null, string $quoteTicker = null): QueryInterface;
}
