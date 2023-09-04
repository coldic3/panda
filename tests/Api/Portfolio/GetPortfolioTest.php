<?php

namespace Panda\Tests\Api\Portfolio;

use Panda\Account\Domain\Model\User;
use Panda\Portfolio\Domain\Model\Portfolio\Portfolio;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class GetPortfolioTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_default'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_a_transaction_item()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Portfolio $portfolio */
        $portfolio = $fixtures['portfolio_default'];

        $uri = sprintf('/portfolios/%s', $portfolio->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_a_transaction_collection()
    {
        $fixtures = $this->loadFixturesFromFile('portfolio.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/portfolios', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'portfolio/get/collection', Response::HTTP_OK);
    }
}
