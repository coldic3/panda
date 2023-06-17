<?php

namespace Panda\Tests\Api\Asset;

use Faker;
use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PostAssetTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ACM',
            'name' => 'Acme Corp.',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_creates_an_asset()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ACM',
            'name' => 'Acme Corp.',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'asset/post/valid', Response::HTTP_CREATED);
    }

    /** @test */
    function it_validates_for_ticker_duplication()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml', 'asset.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ACM',
            'name' => 'Acme Corp.',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/post/invalid_ticker_duplication',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => '',
            'name' => '',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/post/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_no_data()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            // no data
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/post/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_too_long_data()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $faker = Faker\Factory::create();

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => $faker->lexify(str_repeat('?', 256)),
            'name' => $faker->lexify(str_repeat('?', 256)),
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/post/invalid_too_long_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
