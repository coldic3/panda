<?php

namespace Panda\Tests\Api\ExchangeRate;

use Panda\Account\Domain\Model\User;
use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PatchExchangeRateTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => 0.04,
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_updates_an_exchange_rate()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => 0.04,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate/patch/valid', Response::HTTP_OK);
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => null,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate/patch/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @test */
    function it_validates_for_negative_rate()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rates.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $fixtures['exchange_rate_acme_1'];

        $uri = sprintf('/exchange_rates/%s', $exchangeRate->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'rate' => -0.04,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate/patch/invalid_negative_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
