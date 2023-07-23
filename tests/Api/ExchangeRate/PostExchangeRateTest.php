<?php

namespace Panda\Tests\Api\ExchangeRate;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\Asset;
use Symfony\Component\HttpFoundation\Response;

final class PostExchangeRateTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('assets.yaml');

        /** @var Asset $acmeAsset */
        $acmeAsset = $fixtures['asset_acme'];
        /** @var Asset $anotherAsset */
        $anotherAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/exchange_rates', [
            'baseResourceTicker' => $acmeAsset->getTicker(),
            'quoteResourceTicker' => $anotherAsset->getTicker(),
            'rate' => 1.18,
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_creates_an_exchange_rate()
    {
        $fixtures = $this->loadFixturesFromFile('assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $acmeAsset */
        $acmeAsset = $fixtures['asset_acme'];
        /** @var Asset $anotherAsset */
        $anotherAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/exchange_rates', [
            'baseResourceTicker' => $acmeAsset->getTicker(),
            'quoteResourceTicker' => $anotherAsset->getTicker(),
            'rate' => 1.18,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'exchange_rate/post/valid', Response::HTTP_CREATED);
    }

    /** @test */
    function it_validates_for_no_data()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/exchange_rates', [
            // no data
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate/post/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_negative_rate()
    {
        $fixtures = $this->loadFixturesFromFile('assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $acmeAsset */
        $acmeAsset = $fixtures['asset_acme'];
        /** @var Asset $anotherAsset */
        $anotherAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/exchange_rates', [
            'baseResourceTicker' => $acmeAsset->getTicker(),
            'quoteResourceTicker' => $anotherAsset->getTicker(),
            'rate' => -1.18,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'exchange_rate/post/invalid_negative_rate',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
