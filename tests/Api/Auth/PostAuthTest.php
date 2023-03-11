<?php

namespace App\Tests\Api\Auth;

use ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostAuthTest extends JsonApiTestCase
{
    /** @test */
    function it_generates_bearer_token_for_users_credentials()
    {
        $this->loadFixturesFromFile('user.yaml');

        $this->client->request(
            'POST',
            '/auth',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'email' => 'panda@example.com',
                'password' => 'I<3BambooShoots',
            ])
        );

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'auth/post/valid_credentials', Response::HTTP_OK);
    }

    /** @test */
    function it_does_not_generate_bearer_token_for_invalid_credentials()
    {
        $this->loadFixturesFromFile('user.yaml');

        $this->client->request(
            'POST',
            '/auth',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'email' => 'panda@example.com',
                'password' => 'IHateBambooShoots',
            ])
        );

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'auth/post/invalid_credentials', Response::HTTP_UNAUTHORIZED);
    }
}
