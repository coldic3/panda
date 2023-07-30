<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;

final readonly class ExchangeRateLogFactory implements ExchangeRateLogFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function create(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        \DateTimeInterface $startedAt,
        \DateTimeInterface $endedAt,
        OwnerInterface $owner = null,
    ): ExchangeRateLogInterface {
        $exchangeRateLog = new ExchangeRateLog($baseTicker, $quoteTicker, $rate, $startedAt, $endedAt);

        if (null !== $owner) {
            $exchangeRateLog->setOwnedBy($owner);

            return $exchangeRateLog;
        }

        $owner = $this->authorizedUserProvider->provide();

        $exchangeRateLog->setOwnedBy($owner);

        return $exchangeRateLog;
    }
}
