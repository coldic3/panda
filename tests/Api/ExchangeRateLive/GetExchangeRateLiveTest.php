<?php

namespace Panda\Tests\Api\ExchangeRateLive;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\Asset;
use Symfony\Component\HttpFoundation\Response;

final class GetExchangeRateLiveTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_an_exchange_rate_live_item()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_live/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_exchange_rate_live_item_by_base_and_quote_assets()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $baseTicker */
        $baseTicker = $fixtures['asset_acme'];
        /** @var Asset $quoteTicker */
        $quoteTicker = $fixtures['asset_1'];

        $uri = sprintf(
            '/exchange_rate_lives?baseTicker=%s&quoteTicker=%s',
            $baseTicker->getTicker(),
            $quoteTicker->getTicker(),
        );

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_live/get/collection_by_base_and_quote_assets', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_exchange_rate_live_collection()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/exchange_rate_lives', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_live/get/collection', Response::HTTP_OK);
    }
}
