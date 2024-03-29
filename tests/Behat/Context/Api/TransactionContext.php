<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
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
        private readonly EntityManagerInterface $entityManager,
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
     * @When cena sprzedaży akcji wynosi :preciseQuantity :asset
     * @When wybieram do zdeponowania :preciseQuantity :asset
     */
    function i_pass_to_operation(int $preciseQuantity, AssetInterface $asset)
    {
        $this->http->addToPayload('toOperation', [
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
            'quantity' => $preciseQuantity,
        ]);
    }

    /**
     * @When wybieram do zapłaty :preciseQuantity :asset
     * @When /^wybieram do sprzedaży (\d+) akcj(?:|i|e) (spółki "[^"]+")$/
     * @When wybieram do wypłaty :preciseQuantity :asset
     */
    function i_pass_from_operation(int $preciseQuantity, AssetInterface $asset)
    {
        $this->http->addToPayload('fromOperation', [
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
            'quantity' => $preciseQuantity,
        ]);
    }

    /**
     * @When za transakcję płacę :preciseQuantity :asset prowizji
     * @When dodaję opłatę w wysokości :preciseQuantity :asset
     */
    function i_pass_adjustment_operations(int $preciseQuantity, AssetInterface $asset)
    {
        $this->http->addToPayload('adjustmentOperations', [[
            'asset' => $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)),
            'quantity' => $preciseQuantity,
        ]]);
    }

    /**
     * @When podaję datę oraz czas zawarcia transakcji :datetime
     */
    function i_pass_concluded_datetime(\DateTimeImmutable $datetime)
    {
        $this->http->addToPayload('concludedAt', $datetime->format(\DateTimeInterface::RFC3339_EXTENDED));
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
     * @Then posiadam teraz :preciseQuantity :asset w portfelu inwestycyjnym
     * @Then /^posiadam teraz (\d+) akcji (spółki "[^"]+")$/
     */
    function i_own_a_quantity_of_assets_now(int $preciseQuantity, AssetInterface $asset)
    {
        /** @var PortfolioInterface $portfolio */
        $portfolio = $this->clipboard->paste('portfolio');

        $this->entityManager->refresh($portfolio);

        $portfolioItem = $portfolio->getItems()->filter(function (PortfolioItemInterface $item) use ($asset) {
            return $item->getResource()->getTicker() === $asset->getTicker();
        })->first();

        Assert::notFalse($portfolioItem);

        Assert::same($portfolioItem->getLongQuantity(), $preciseQuantity);
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
     * @Then na :position pozycji jest transakcja zakupu :toQuantity akcji spółki :toAsset za :fromPreciseQuantity :fromAsset
     */
    function at_position_there_is_an_ask_transaction_currency(string $position, int $toQuantity, AssetInterface $toAsset, int $fromPreciseQuantity, AssetInterface $fromAsset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'ask');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($fromAsset)));
        Assert::same($item['fromOperation']['quantity'], $fromPreciseQuantity);
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($toAsset)));
        Assert::same($item['toOperation']['quantity'], $toQuantity);
    }

    /**
     * @Then na :position pozycji jest transakcja zakupu :toQuantity akcji spółki :toAsset za :fromQuantity akcję spółki :fromAsset
     */
    function at_position_there_is_an_ask_transaction_asset(string $position, int $toQuantity, AssetInterface $toAsset, int $fromQuantity, AssetInterface $fromAsset)
    {
        $this->at_position_there_is_an_ask_transaction_currency($position, $toQuantity, $toAsset, $fromQuantity, $fromAsset);
    }

    /**
     * @Then na :position pozycji jest transakcja wypłaty :preciseQuantity :asset
     */
    function at_position_there_is_a_withdraw_transaction(string $position, int $preciseQuantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'withdraw');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['fromOperation']['quantity'], $preciseQuantity);
    }

    /**
     * @Then na :position pozycji jest transakcja depozytu :preciseQuantity :asset
     */
    function at_position_there_is_a_deposit_transaction(string $position, int $preciseQuantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'deposit');
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['toOperation']['quantity'], $preciseQuantity);
    }

    /**
     * @Then na :position pozycji jest transakcja pobrania opłaty :preciseQuantity :asset
     */
    function at_position_there_is_a_fee_transaction(string $position, int $preciseQuantity, AssetInterface $asset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'fee');
        Assert::same($item['adjustmentOperations'][0]['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['adjustmentOperations'][0]['quantity'], $preciseQuantity);
    }

    /**
     * @Then na :position pozycji jest transakcja sprzedaży :fromQuantity akcji spółki :fromAsset za :toPreciseQuantity :toAsset
     */
    function at_position_there_is_a_bid_transaction(string $position, int $fromQuantity, AssetInterface $fromAsset, int $toPreciseQuantity, AssetInterface $toAsset)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $indexMap = array_flip(self::POSITIONS);
        $item = $response['hydra:member'][$indexMap[$position]];
        $this->clipboard->copy('lastTransaction', $item);

        Assert::same($item['type'], 'bid');
        Assert::same($item['fromOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($fromAsset)));
        Assert::same($item['fromOperation']['quantity'], $fromQuantity);
        Assert::same($item['toOperation']['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($toAsset)));
        Assert::same($item['toOperation']['quantity'], $toPreciseQuantity);
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
     * @Then za tę transakcję zapłacono :preciseQuantity :asset prowizji
     */
    function this_transaction_cost_provision(int $preciseQuantity, AssetInterface $asset)
    {
        $item = $this->clipboard->paste('lastTransaction');

        Assert::same($item['adjustmentOperations'][0]['asset'], $this->iriConverter->getIriFromResource(AssetResource::fromModel($asset)));
        Assert::same($item['adjustmentOperations'][0]['quantity'], $preciseQuantity);
    }
}
