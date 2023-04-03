<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class TransactionContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly IriConverterInterface $iriConverter,
    ) {
    }

    /**
     * @When rozpoczynam nową transakcję
     */
    function i_start_a_new_transaction()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/transactions',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^wybieram typ transakcji "([^"]+)"$/
     */
    function i_pass_transaction_type(string $type)
    {
        $this->http->addToPayload('type', $type);
    }

    /**
     * @When /^wybieram do zakupu (\d+) akcj(?:|i|e) (spółki "[^"]+")$/
     * @When cena sprzedaży akcji wynosi :quantity :asset
     * @When wybieram do zdeponowania :quantity :asset
     * @When /^wybieram do przeniesienia (\d+) akcj(?:|i|e) (spółki "[^"]+")$/
     */
    function i_pass_to_operation(int $quantity, AssetInterface $asset)
    {
        $this->http->addToPayload('to', [
            'resource' => $this->iriConverter->getIriFromResource($asset),
            'quantity' => $quantity,
        ]);
    }

    /**
     * @When wybieram do zapłaty :quantity :asset
     * @When /^wybieram do sprzedaży (\d+) akcj(?:|i|e) (spółki "[^"]+")$/
     * @When wybieram do wypłaty :quantity :asset
     */
    function i_pass_from_operation(int $quantity, AssetInterface $asset)
    {
        $this->http->addToPayload('from', [
            'resource' => $this->iriConverter->getIriFromResource($asset),
            'quantity' => $quantity,
        ]);
    }

    /**
     * @When za transakcję płacę :quantity :asset prowizji
     * @When dodaję opłatę w wysokości :quantity :asset
     */
    function i_pass_adjustment_operations(float $quantity, AssetInterface $asset)
    {
        $this->http->addToPayload('adjustments', [[
            'resource' => $this->iriConverter->getIriFromResource($asset),
            'quantity' => $quantity,
        ]]);
    }

    /**
     * @When podaję datę oraz czas zawarcia transakcji
     */
    function i_pass_concluded_datetime()
    {
        $this->http->addToPayload('concluded_at', '2023-04-03T21:16:43+00:00');
    }

    /**
     * @When zatwierdzam transakcję
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then transakcja kończy się sukcesem
     */
    function the_transaction_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_CREATED);
    }

    /**
     * @Then widzę, że zapłaciłem za jedną akcję :grossQuantity :grossAsset brutto oraz :netQuantity :netAsset netto
     * @Then widzę, że sprzedałem jedną akcję za :grossQuantity :grossAsset brutto oraz :netQuantity :netAsset netto
     * @Then widzę, że zdeponowałem :grossQuantity :grossAsset brutto oraz :netQuantity :netAsset netto
     * @Then widzę, że wycofałem aktywa o kwocie :grossQuantity :grossAsset brutto oraz :netQuantity :netAsset netto
     */
    function the_transaction_cost_for_a_single_asset(float $grossQuantity, AssetInterface $grossAsset, float $netQuantity, AssetInterface $netAsset)
    {
        Assert::same($grossAsset->getId(), $netAsset->getId());

        $response = $this->http->getResponse();
        $transaction = json_decode($response->getContent(), true);

        Assert::same($transaction['cost']['quantity']['gross'], $grossQuantity);
        Assert::same($transaction['cost']['quantity']['net'], $netQuantity);
        Assert::same($transaction['cost']['resource'], $this->iriConverter->getIriFromResource($grossAsset));
    }

    /**
     * @Then posiadam teraz :quantity :asset w portfelu inwestycyjnym
     * @Then /^posiadam teraz (\d+) akcji (spółki "[^"]+")$/
     */
    function i_own_a_quantity_of_assets_now(int $quantity, AssetInterface $asset)
    {
    }
}