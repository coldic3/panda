<?php

namespace Panda\Tests\Api\ExchangeRateLog;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class DeleteExchangeRateLogTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var ExchangeRateLog $exchangeRateLog */
        $exchangeRateLog = $fixtures['exchange_rate_log_usd_pln_1'];

        $uri = sprintf('/exchange_rate_logs/%s', $exchangeRateLog->getId());

        $this->request(HttpMethodEnum::DELETE, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_deletes_an_exchange_rate_log()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLog $exchangeRateLog */
        $exchangeRateLog = $fixtures['exchange_rate_log_usd_pln_1'];

        $uri = sprintf('/exchange_rate_logs/%s', $exchangeRateLog->getId());

        $this->request(HttpMethodEnum::DELETE, $uri, [], $this->generateAuthorizationHeader($user));

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
}
