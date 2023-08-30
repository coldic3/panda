<?php

declare(strict_types=1);

namespace Panda\Tests\Api\Portfolio;

use Panda\Account\Domain\Model\User;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class XPortfolioIdHttpHeaderTest extends ApiTestCase
{
    /** @test */
    function it_uses_x_portfolio_id_http_header()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var PortfolioInterface $additionalPortfolio */
        $additionalPortfolio = $fixtures['portfolio_additional'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ABC',
            'name' => 'Abrakadabra Inc.',
        ], [
            'X-Portfolio-Id' => $additionalPortfolio->getId()->toRfc4122(),
            ...$this->generateAuthorizationHeader($user),
        ]);

        $this->assertResponse($this->client->getResponse(), 'portfolio/header/valid_portfolio_id', Response::HTTP_CREATED);
    }

    /** @test */
    function it_validates_for_invalid_uuid_in_x_portfolio_id_http_header()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ABC',
            'name' => 'Abrakadabra Inc.',
        ], [
            'HTTP_X-Portfolio-Id' => 'invalid-uuid',
            ...$this->generateAuthorizationHeader($user),
        ]);

        $this->assertResponse($this->client->getResponse(), 'portfolio/header/invalid_portfolio_id_invalid_uuid', Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    function it_validates_for_nonexistent_portfolio_in_x_portfolio_id_http_header()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'portfolio.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::POST, '/assets', [
            'ticker' => 'ABC',
            'name' => 'Abrakadabra Inc.',
        ], [
            'HTTP_X-Portfolio-Id' => '35af25e8-f19c-4db9-a0a1-f298573dad37',
            ...$this->generateAuthorizationHeader($user),
        ]);

        $this->assertResponse($this->client->getResponse(), 'portfolio/header/invalid_portfolio_id_not_found', Response::HTTP_BAD_REQUEST);
    }
}
