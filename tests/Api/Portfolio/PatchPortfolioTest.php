<?php

namespace Panda\Tests\Api\Portfolio;

use Faker;
use Panda\Account\Domain\Model\User;
use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PatchPortfolioTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_default'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => 'My Portfolio',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_updates_a_portfolio_name()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_additional'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => 'Fancy Portfolio',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/patch/rename', Response::HTTP_OK);
    }

    /** @test */
    function it_ignores_not_editable_property()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_additional'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'default' => false,
            'mainResource' => [
                'ticker' => 'PLN',
                'name' => 'Polish Zloty',
            ],
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/patch/nothing_changed', Response::HTTP_OK);
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_additional'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => '',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'portfolio/patch/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @test */
    function it_validates_for_too_long_data()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_additional'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $faker = Faker\Factory::create();

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => $faker->lexify(str_repeat('?', 256)),
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'portfolio/patch/invalid_too_long_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
