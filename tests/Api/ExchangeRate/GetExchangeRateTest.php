<?php

namespace Panda\Tests\Api\ExchangeRate;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\Asset;
use Symfony\Component\HttpFoundation\Response;

final class GetExchangeRateTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_an_asset_item()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_asset_item_by_base_and_quote_assets()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $baseTicker */
        $baseTicker = $fixtures['asset_acme'];
        /** @var Asset $quoteTicker */
        $quoteTicker = $fixtures['asset_1'];

        $uri = sprintf(
            '/exchange_rates?baseTicker=%s&quoteTicker=%s',
            $baseTicker->getTicker(),
            $quoteTicker->getTicker(),
        );

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate/get/collection_by_base_and_quote_assets', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_asset_collection()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/exchange_rates', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate/get/collection', Response::HTTP_OK);
    }
}
