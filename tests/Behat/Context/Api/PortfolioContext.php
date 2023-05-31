<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Webmozart\Assert\Assert;

class PortfolioContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly IriConverterInterface $iriConverter,
    ) {
    }

    /**
     * @When wyświetlam skład portfela
     */
    function i_view_portfolio_details()
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf('/portfolio/%s', $this->clipboard->paste('portfolio')->getId()),
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @Then widzę :count aktywów wchodzących w jego skład
     */
    function i_see_number_of_assets_in_the_portfolio(int $count)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::same($response['hydra:totalItems'], $count);
    }

    /**
     * @Then /^widzę (aktywo "[^"]+") o nazwie "([^"]+)" w ilości ((-?)\d+)$/
     */
    function i_see_the_asset_with_name_and_quantity(string $ticker, string $name, int $quantity)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $items = $response['hydra:member']['items'];

        $itemFound = false;

        foreach ($items as $item) {
            if ($item['resource']['ticker'] !== $ticker) {
                continue;
            }

            $itemFound = true;

            Assert::same($item['resource']['ticker'], $ticker);
            Assert::same($item['resource']['name'], $name);
            Assert::same($item['quantity'][$quantity >= 0 ? 'long' : 'short'], abs($quantity));
        }

        Assert::true($itemFound);
    }
}
