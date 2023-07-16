<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Trade\Domain\Model\Asset\Asset;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRate;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
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
        Assert::notNull($baseAssetTicker = $tickers[0] ?? null);
        Assert::notNull($quoteAssetTicker = $tickers[1] ?? null);

        $baseAsset = $this->entityManager->getRepository(Asset::class)->findOneBy(['ticker' => $baseAssetTicker]);
        $quoteAsset = $this->entityManager->getRepository(Asset::class)->findOneBy(['ticker' => $quoteAssetTicker]);

        Assert::isInstanceOf(
            $exchangeRate = $this->entityManager
                ->getRepository(ExchangeRate::class)
                ->findOneBy(['baseAsset' => $baseAsset, 'quoteAsset' => $quoteAsset]),
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
        Assert::notNull($baseAssetTicker = $tickers[0] ?? null);
        Assert::notNull($quoteAssetTicker = $tickers[1] ?? null);

        $baseAsset = $this->entityManager->getRepository(Asset::class)->findOneBy(['ticker' => $baseAssetTicker]);
        $quoteAsset = $this->entityManager->getRepository(Asset::class)->findOneBy(['ticker' => $quoteAssetTicker]);

        Assert::notNull($baseAssetId = $baseAsset?->getId());
        Assert::notNull($quoteAssetId = $quoteAsset?->getId());

        return ['baseId' => $baseAssetId, 'quoteId' => $quoteAssetId];
    }
}
