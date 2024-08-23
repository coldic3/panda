<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;

final readonly class ExchangeRateLiveFactory implements ExchangeRateLiveFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function create(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        ?OwnerInterface $owner = null,
    ): ExchangeRateLiveInterface {
        $exchangeRateLive = new ExchangeRateLive($baseTicker, $quoteTicker, $rate);

        if (null !== $owner) {
            $exchangeRateLive->setOwnedBy($owner);

            return $exchangeRateLive;
        }

        $owner = $this->authorizedUserProvider->provide();

        $exchangeRateLive->setOwnedBy($owner);

        return $exchangeRateLive;
    }
}
