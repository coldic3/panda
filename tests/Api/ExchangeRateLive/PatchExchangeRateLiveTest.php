<?php

namespace Panda\Tests\Api\ExchangeRateLive;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PatchExchangeRateLiveTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => 0.04,
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_updates_an_exchange_rate_live()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => 0.04,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_live/patch/valid', Response::HTTP_OK);
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => null,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_live/patch/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @test */
    function it_validates_for_negative_rate()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => -0.04,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_live/patch/invalid_negative_or_zero_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @test */
    function it_validates_for_zero_rate()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_lives.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRateLive $exchangeRateLive */
        $exchangeRateLive = $fixtures['exchange_rate_live_acme_1'];

        $uri = sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => 0,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_live/patch/invalid_negative_or_zero_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
