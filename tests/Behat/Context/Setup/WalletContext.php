<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;

class WalletContext implements Context
{
    public function __construct(private readonly AssetContext $assetContext)
    {
    }

    /**
     * @Given /^posiadam (\d+) ([^"]+) w portfelu inwestycyjnym$/
     * @Given /^posiadam (\d+) akcji spółki "([^"]+)" o nazwie "([^"]+)"$/
     */
    function blank(float $quantity, string $ticker, ?string $name = null)
    {
        $this->assetContext->there_is_an_asset_with_ticker_and_name($ticker, $name ?? $ticker);
    }
}
