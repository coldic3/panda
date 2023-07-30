<?php

namespace Panda\Tests\Api\ExchangeRateLive;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class DeleteExchangeRateLiveTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::DELETE, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_deletes_an_exchange_rate_live()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::DELETE, $uri, [], $this->generateAuthorizationHeader($user));

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
}
