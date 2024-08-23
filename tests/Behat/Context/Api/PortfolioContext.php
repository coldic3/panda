<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
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
            sprintf('/portfolios/%s', $this->clipboard->paste('portfolio')->getId()),
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @When tworzę nowy portfel
     */
    function i_create_a_new_portfolio()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/portfolios',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^modyfikuję (portfel "[^"]+")$/
     */
    function i_edit_the_portfolio(PortfolioInterface $portfolio)
    {
        $this->http->initialize(
            HttpMethodEnum::PATCH,
            sprintf('/portfolios/%s', $portfolio->getId()),
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^wybieram (portfel "[^"]+") jako domyślny$/
     */
    function i_change_default_portfolio(PortfolioInterface $portfolio)
    {
        $this->http->initialize(
            HttpMethodEnum::PATCH,
            sprintf('/portfolios/%s/default', $portfolio->getId()),
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When podaję nazwę :name
     */
    function i_pass_a_name(string $name)
    {
        $this->http->addToPayload('name', $name);
    }

    /**
     * @When podaję ticker głównego aktywa :ticker
     */
    function i_pass_a_main_resource_tickr(string $ticker)
    {
        $mainResource = $this->http->getPayloadElement('mainResource') ?? [];
        $mainResource['ticker'] = $ticker;

        $this->http->addToPayload('mainResource', $mainResource);
    }

    /**
     * @When podaję nazwę głównego aktywa :name
     */
    function i_pass_a_main_resource_name(string $name)
    {
        $mainResource = $this->http->getPayloadElement('mainResource') ?? [];
        $mainResource['name'] = $name;

        $this->http->addToPayload('mainResource', $mainResource);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then dodawanie portfela kończy się sukcesem
     */
    function the_portfolio_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_CREATED);
    }

    /**
     * @Then edycja portfela kończy się sukcesem
     * @Then zmiana portfela domyślnego kończy się sukcesem
     */
    function the_portfolio_modification_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @Then widzę, że portfel jest portfelem domyślnym
     */
    function i_see_portfolio_is_a_default_one()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::true($response['default']);
    }

    /**
     * @Then widzę, że portfel nie jest portfelem domyślnym
     * @Then /^widzę, że (portfel "[^"]+") nie jest już portfelem domyślnym$/
     */
    function i_see_portfolio_is_not_a_default_one(?PortfolioInterface $portfolio = null)
    {
        if (null !== $portfolio) {
            Assert::false($portfolio->isDefault());

            return;
        }

        $response = json_decode($this->http->getResponse()->getContent(false), true);
        Assert::isInstanceOf(
            $portfolio = $this->iriConverter->getResourceFromIri($response['@id']),
            PortfolioResource::class,
        );

        Assert::false($response['default']);
        Assert::false($portfolio->default);
    }

    /**
     * @Then widzę, że portfel ma nazwę :name
     */
    function i_see_portfolio_is_named(string $name)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::same($response['name'], $name);
    }

    /**
     * @Then widzę :count aktywów wchodzących w jego skład
     */
    function i_see_number_of_assets_in_the_portfolio(int $count)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::count($response['items'], $count);
    }

    /**
     * @Then /^widzę aktywo "([^"]+)" o nazwie "([^"]+)" w ilości ((-?)\d+)$/
     */
    function i_see_the_asset_with_name_and_quantity(string $ticker, string $name, int $quantity)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $items = $response['items'];

        $itemFound = false;

        foreach ($items as $item) {
            if ($item['resource']['ticker'] !== $ticker) {
                continue;
            }

            $itemFound = true;

            Assert::same($item['resource']['ticker'], $ticker);
            Assert::same($item['resource']['name'], $name);
            Assert::same(
                // FIXME: This is a temporary solution for currencies with fractional units.
                $item['quantity'][$quantity >= 0 ? 'long' : 'short'] / ('PLN' === $ticker ? 100 : 1),
                abs($quantity)
            );
        }

        Assert::true($itemFound);
    }
}
