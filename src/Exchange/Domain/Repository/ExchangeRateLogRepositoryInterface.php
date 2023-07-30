<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Repository;

use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Symfony\Component\Uid\Uuid;

interface ExchangeRateLogRepositoryInterface extends RepositoryInterface
{
    public function save(ExchangeRateLogInterface $exchangeRateLog): void;

    public function remove(ExchangeRateLogInterface $exchangeRateLog): void;

    public function findById(Uuid $id): ?ExchangeRateLogInterface;

    public function findInDatetimeRange(
        string $baseTicker,
        string $quoteTicker,
        \DateTimeInterface $fromDatetime,
        \DateTimeInterface $toDatetime
    ): ?ExchangeRateLogInterface;

    public function defaultQuery(
        string $baseTicker = null,
        string $quoteTicker = null,
        \DateTimeInterface $startedAt = null,
        \DateTimeInterface $endedAt = null,
    ): QueryInterface;
}
