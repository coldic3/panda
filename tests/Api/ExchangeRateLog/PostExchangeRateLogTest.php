<?php

namespace Panda\Tests\Api\ExchangeRateLog;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PostExchangeRateLogTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 3.99,
            'startedAt' => '2023-07-26 00:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_creates_an_exchange_rate()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 3.99,
            'startedAt' => '2023-07-26 00:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate_log/post/valid', Response::HTTP_CREATED);
    }

    /** @test */
    function it_validates_for_no_data()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            // no data
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_negative_rate()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => -3.99,
            'startedAt' => '2023-07-26 00:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_negative_or_zero_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_zero_rates()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 0,
            'startedAt' => '2023-07-26 00:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_negative_or_zero_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_the_same_base_and_quote_tickers()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'USD',
            'rate' => 3.99,
            'startedAt' => '2023-07-26 00:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_same_base_and_quote_tickers',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_overlapped_start_date()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 3.99,
            'startedAt' => '2023-07-17 01:00:00',
            'endedAt' => '2023-07-26 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_overlapped_start_date',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_overlapped_start_and_end_date()
    {
        $fixtures = $this->loadFixturesFromFile('exchange_rate_logs.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 3.99,
            'startedAt' => '2023-07-09 00:00:00',
            'endedAt' => '2023-07-13 23:59:59',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_overlapped_end_date',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_end_date_less_than_start_date()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rate_logs', [
            'baseTicker' => 'USD',
            'quoteTicker' => 'PLN',
            'rate' => 3.99,
            'startedAt' => '2023-07-26 23:59:59',
            'endedAt' => '2023-07-26 00:00:00',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate_log/post/invalid_end_date_less_than_start_date',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
