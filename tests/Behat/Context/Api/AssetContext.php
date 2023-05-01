<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Exception\ItemNotFoundException;
use Behat\Behat\Context\Context;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Panda\Trade\Infrastructure\ApiResource\AssetResource;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class AssetContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly IriConverterInterface $iriConverter,
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
            HttpMethodEnum::PATCH,
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
     */
    function the_asset_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_CREATED);
    }

    /**
     * @Then edycja aktywa kończy się sukcesem
     */
    function the_asset_modification_ends_with_a_success()
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
     * @Then edycja aktywa kończy się niepowodzeniem
     * @Then usuwanie aktywa kończy się niepowodzeniem
     */
    function the_asset_edition_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @Then aktywo zostało dodane do listy aktywów
     */
    function the_asset_has_been_created()
    {
        /** @var AssetResource $resource */
        $resource = $this->http->getResource();

        Assert::notNull($this->assetRepository->findById($resource->id));
    }

    /**
     * @Then aktywo zostało usunięte z listy aktywów
     */
    function the_asset_has_been_deleted()
    {
        Assert::throws(
            fn () => $this->iriConverter->getResourceFromIri($this->http->getPath()),
            ItemNotFoundException::class,
        );
    }

    /**
     * @Then aktywo zmienia swoją nazwę na :name
     */
    function the_asset_changes_its_name_to(string $name)
    {
        /** @var AssetResource $resource */
        $resource = $this->http->getResource();

        Assert::notNull($asset = $this->assetRepository->findById($resource->id));
        Assert::same($asset->getName(), $name);
    }

    /**
     * @Then aktywo zmienia swój ticker na :ticker
     */
    function the_asset_changes_its_ticker_to(string $ticker)
    {
        /** @var AssetResource $resource */
        $resource = $this->http->getResource();

        Assert::notNull($asset = $this->assetRepository->findById($resource->id));
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

    /**
     * @Then informacje o aktywie nie zostają wyświetlane
     */
    function the_user_information_are_not_showed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }
}
