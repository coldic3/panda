<?php

namespace Panda\Tests\Api\Portfolio;

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
        $this->request(HttpMethodEnum::POST, '/portfolios', [
            'name' => 'My Portfolio',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_creates_a_portfolio()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/portfolios', [
            'name' => 'My Portfolio',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/post/valid_first_portfolio', Response::HTTP_CREATED);
    }

    /** @test */
    function it_ignores_a_default_property()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/portfolios', [
            'name' => 'My Portfolio',
            'default' => false,
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/post/valid_first_portfolio', Response::HTTP_CREATED);
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/portfolios', [
            'name' => '',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'portfolio/_common/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_no_data()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/portfolios', [
            // no data
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'portfolio/_common/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_too_long_data()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $faker = Faker\Factory::create();

        $this->request(HttpMethodEnum::POST, '/portfolios', [
            'name' => $faker->lexify(str_repeat('?', 256)),
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'portfolio/_common/invalid_too_long_data',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
