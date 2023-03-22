<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class AssetContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly AssetRepositoryInterface $assetRepository,
    ) {
    }

    /**
     * @When tworzę nowe aktywo
     */
    function i_create_a_new_asset()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/assets',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^modyfikuję (aktywo "[^"]+")$/
     */
    function i_edit_the_asset(AssetInterface $asset)
    {
        $this->http->initialize(
            HttpMethodEnum::PUT,
            sprintf('/assets/%s', $asset->getId()),
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^usuwam (aktywo "[^"]+")$/
     */
    function i_delete_the_asset(AssetInterface $asset)
    {
        $this->http->initialize(
            HttpMethodEnum::DELETE,
            sprintf('/assets/%s', $asset->getId()),
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @When /^wyświetlam (aktywo "[^"]+")$/
     */
    function i_show_account_information(AssetInterface $asset)
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf('/assets/%s', $asset->getId()),
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @When wyświetlam listę aktywów
     */
    function i_show_accounts_information()
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            '/assets',
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @When podaję ticker :ticker
     */
    function i_pass_a_ticker(string $ticker)
    {
        $this->http->addToPayload('ticker', $ticker);
    }

    /**
     * @When podaję nazwę :name
     */
    function i_pass_a_name(string $name)
    {
        $this->http->addToPayload('name', $name);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     * @When nie podaję żadnych danych
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then dodawanie aktywa kończy się sukcesem
     * @Then edycja aktywa kończy się sukcesem
     */
    function the_asset_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @Then usuwanie aktywa kończy się sukcesem
     */
    function the_asset_deletion_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NO_CONTENT);
    }

    /**
     * @Then dodawanie aktywa kończy się niepowodzeniem
     */
    function the_asset_creation_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Then aktywo zostało dodane do listy aktywów
     */
    function the_asset_has_been_created()
    {
        Assert::notNull($this->assetRepository->findById($this->http->getPayloadElement('@id')));
    }

    /**
     * @Then aktywo nie zostało dodane do listy aktywów
     * @Then aktywo zostało usunięte z listy aktywów
     */
    function the_asset_has_not_been_created()
    {
        Assert::null($this->assetRepository->findById($this->http->getPayloadElement('@id')));
    }

    /**
     * @Then aktywo zmienia swoją nazwę na :name
     */
    function the_asset_changes_its_name_to(string $name)
    {
        Assert::notNull($asset = $this->assetRepository->findById($this->http->getPayloadElement('@id')));
        Assert::same($asset->getName(), $name);
    }

    /**
     * @Then aktywo zmienia swój ticker na :ticker
     */
    function the_asset_changes_its_ticker_to(string $ticker)
    {
        Assert::notNull($asset = $this->assetRepository->findById($this->http->getPayloadElement('@id')));
        Assert::same($asset->getTicker(), $ticker);
    }

    /**
     * @Then informacje o aktywie zostają wyświetlane
     * @Then informacje o aktywach zostają wyświetlone
     */
    function the_user_information_are_showed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::notEmpty($this->http->getResponse()->getContent());
    }
}
