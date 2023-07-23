<?php

namespace Panda\Tests\Api\ExchangeRate;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class DeleteExchangeRateTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::DELETE, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_deletes_an_exchange_rate()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::DELETE, $uri, [], $this->generateAuthorizationHeader($user));

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
}
