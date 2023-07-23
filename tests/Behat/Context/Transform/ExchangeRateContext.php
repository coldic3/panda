<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Webmozart\Assert\Assert;

class ExchangeRateContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @Transform /^"([^"]+\/[^"]+)"$/
     */
    public function exchangeRate(string $baseQuote): ExchangeRateLiveInterface
    {
        $tickers = explode('/', $baseQuote);
        Assert::count($tickers, 2);
        Assert::notNull($baseTicker = $tickers[0] ?? null);
        Assert::notNull($quoteTicker = $tickers[1] ?? null);

        Assert::isInstanceOf(
            $exchangeRateLive = $this->entityManager
                ->getRepository(ExchangeRateLive::class)
                ->findOneBy([
                    'baseTicker' => $baseTicker,
                    'quoteTicker' => $quoteTicker,
                ]),
            ExchangeRateLiveInterface::class
        );

        return $exchangeRateLive;
    }

    /**
     * @Transform :baseQuote
     */
    public function baseQuote(string $baseQuote): array
    {
        $tickers = explode('/', $baseQuote);
        Assert::count($tickers, 2);
        Assert::notNull($baseTicker = $tickers[0] ?? null);
        Assert::notNull($quoteTicker = $tickers[1] ?? null);

        return ['base' => $baseTicker, 'quote' => $quoteTicker];
    }
}
