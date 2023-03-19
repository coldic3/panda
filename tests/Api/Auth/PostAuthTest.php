<?php

namespace Panda\Tests\Api\Auth;

use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PostAuthTest extends ApiTestCase
{
    /** @test */
    function it_generates_bearer_token_for_users_credentials()
    {
        $this->loadFixturesFromFile('user.yaml');

        $this->request(HttpMethodEnum::POST, '/auth', [
            'email' => 'panda@example.com',
            'password' => 'I<3BambooShoots',
        ]);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'auth/post/valid_credentials', Response::HTTP_OK);
    }

    /** @test */
    function it_does_not_generate_bearer_token_for_invalid_credentials()
    {
        $this->loadFixturesFromFile('user.yaml');

        $this->request(HttpMethodEnum::POST, '/auth', [
            'email' => 'panda@example.com',
            'password' => 'IHateBambooShoots',
        ]);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'auth/post/invalid_credentials', Response::HTTP_UNAUTHORIZED);
    }
}
