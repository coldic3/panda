<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Infrastructure\ApiResource\AssetResource;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class TransactionContext implements Context
{
    use EnableClipboardTrait;

    private const POSITIONS = ['pierwszej', 'drugiej', 'trzeciej', 'czwartej', 'piątej', 'szóstej', 'siódmej'];

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
     * @When wyświetlam historię transakcji
     */
    function i_view_transactions_history()
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            '/transactions',
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
    }

    /**
     * @When wybieram filtrowanie po aktywie :asset z którego nastąpiła wymiana
     */
    function i_filter_transactions_by_from_operation_asset(AssetInterface $asset)
    {
        $this->http->addQueryParameter('fromOperation.asset.id', $asset->getId()->toRfc4122());
        $this->http->finalize();
    }

    /**
     * @When wybieram filtrowanie po aktywie :asset na który nastąpiła wymiana
     */
    function i_filter_transactions_by_to_operation_asset(AssetInterface $asset)
    {
        $this->http->addQueryParameter('toOperation.asset.id', $asset->getId()->toRfc4122());
        $this->http->finalize();
    }

    /**
     * @When wybieram filtrowanie po dacie dokonania transakcji od :datetime
     */
    function i_filter_transactions_by_concluded_at_from(\DateTimeImmutable $datetime)
    {
        $this->http->addQueryParameter('concludedAt[after]', $datetime->getTimestamp());
        $this->http->finalize();
    }

    /**
     * @When wybieram filtrowanie po dacie dokonania transakcji do :datetime
     */
    function i_filter_transactions_by_concluded_at_to(\DateTimeImmutable $datetime)
    {
        $this->http->addQueryParameter('concludedAt[before]', $datetime->getTimestamp());
        $this->http->finalize();
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
     */
    function i_pass_to_operation(int $quantity, AssetInterface $asset)
    {
        $this->http->addToPayload('toOperation', [
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
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
        $this->http->addToPayload('fromOperation', [
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
            'quantity' => $quantity,
        ]);
    }

    /**
     * @When za transakcję płacę :quantity :asset prowizji
     * @When dodaję opłatę w wysokości :quantity :asset
     */
    function i_pass_adjustment_operations(float $quantity, AssetInterface $asset)
    {
        // FIXME: This is a temporary solution for currencies with fractional units.
        if ('PLN' === $asset->getTicker()) {
            $quantity = (int) ($quantity * 100);
        }

        $this->http->addToPayload('adjustmentOperations', [[
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
            'quantity' => $quantity,
        ]]);
    }

    /**
     * @When podaję datę oraz czas zawarcia transakcji
     */
    function i_pass_concluded_datetime()
    {
        $this->http->addToPayload('concludedAt', '2023-04-03T21:16:43+00:00');
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
    }

    /**
     * @Then posiadam teraz :quantity :asset w portfelu inwestycyjnym
     * @Then /^posiadam teraz (\d+) akcji (spółki "[^"]+")$/
     */
    function i_own_a_quantity_of_assets_now(int $quantity, AssetInterface $asset)
    {
    }

    /**
     * @Then /^widzę (\d+) transakcj(ę|e|i)$/
     */
    function i_see_number_of_transactions(int $count)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::same($response['hydra:totalItems'], $count);
    }

    /**
     * @Then na :position pozycji jest transakcja zakupu :toQuantity akcji spółki :toAsset za :fromQuantity akcję spółki :fromAsset
     * @Then na :position pozycji jest transakcja zakupu :toQuantity akcji spółki :toAsset za :fromQuantity :fromAsset
     */
    function at_position_there_is_an_ask_transaction(string $position, int $toQuantity, AssetInterface $toAsset, int $fromQuantity, AssetInterface $fromAsset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'ask');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($fromAsset)));
        Assert::same($item['fromOperation']['quantity'], $fromQuantity);
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($toAsset)));
        Assert::same($item['toOperation']['quantity'], $toQuantity);
    }

    /**
     * @Then na :position pozycji jest transakcja wypłaty :quantity :asset
     */
    function at_position_there_is_a_withdraw_transaction(string $position, int $quantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'withdraw');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['fromOperation']['quantity'], $quantity);
    }

    /**
     * @Then na :position pozycji jest transakcja depozytu :quantity :asset
     */
    function at_position_there_is_a_deposit_transaction(string $position, int $quantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'deposit');
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['toOperation']['quantity'], $quantity);
    }

    /**
     * @Then na :position pozycji jest transakcja pobrania opłaty :quantity :asset
     */
    function at_position_there_is_a_fee_transaction(string $position, int $quantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'fee');
        Assert::same($item['adjustmentOperations'][0]['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['adjustmentOperations'][0]['quantity'], $quantity);
    }

    /**
     * @Then na :position pozycji jest transakcja sprzedaży :fromQuantity akcji spółki :fromAsset za :toQuantity :toAsset
     */
    function at_position_there_is_a_bid_transaction(string $position, int $fromQuantity, AssetInterface $fromAsset, int $toQuantity, AssetInterface $toAsset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'bid');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($fromAsset)));
        Assert::same($item['fromOperation']['quantity'], $fromQuantity);
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($toAsset)));
        Assert::same($item['toOperation']['quantity'], $toQuantity);
    }

    /**
     * @Then transakcja ta została wykonana w dniu :datetime
     */
    function this_transaction_took_place_at(\DateTimeImmutable $datetime)
    {
        $item = $this->clipboard->paste('lastTransaction');

        Assert::same($item['concludedAt'], $datetime->format(\DateTimeInterface::ATOM));
    }

    /**
     * @Then za tę transakcję zapłacono :quantity :asset prowizji
     */
    function this_transaction_cost_provision(int $quantity, AssetInterface $asset)
    {
        $item = $this->clipboard->paste('lastTransaction');

        Assert::same($item['adjustmentOperations'][0]['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['adjustmentOperations'][0]['quantity'], $quantity);
    }
}
