<?php

namespace Panda\Tests\Api\ExchangeRateLog;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class GetExchangeRateLogTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var ExchangeRateLog $exchangeRateLog */
        $exchangeRateLog = $fixtures['exchange_rate_log_usd_pln_1'];

        $uri = sprintf('/exchange_rate_logs/%s', $exchangeRateLog->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_an_exchange_rate_log_item()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLog $exchangeRateLog */
        $exchangeRateLog = $fixtures['exchange_rate_log_usd_pln_1'];

        $uri = sprintf('/exchange_rate_logs/%s', $exchangeRateLog->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_log/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_exchange_rate_log_collection_by_base_and_quote_tickers()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/exchange_rate_logs?baseTicker=USD&quoteTicker=PLN', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_log/get/collection_by_base_and_quote_tickers', Response::HTTP_OK);
    }
}
