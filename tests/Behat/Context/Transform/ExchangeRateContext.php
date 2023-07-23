<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Exchange\Domain\Model\ExchangeRateInterface;
use Webmozart\Assert\Assert;

class ExchangeRateContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @Transform /^"([^"]+\/[^"]+)"$/
     */
    public function exchangeRate(string $baseQuote): ExchangeRateInterface
    {
        $tickers = explode('/', $baseQuote);
        Assert::count($tickers, 2);
        Assert::notNull($baseTicker = $tickers[0] ?? null);
        Assert::notNull($quoteTicker = $tickers[1] ?? null);

        Assert::isInstanceOf(
            $exchangeRate = $this->entityManager
                ->getRepository(ExchangeRate::class)
                ->findOneBy([
                    'baseTicker' => $baseTicker,
                    'quoteTicker' => $quoteTicker,
                ]),
            ExchangeRateInterface::class
        );

        return $exchangeRate;
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
