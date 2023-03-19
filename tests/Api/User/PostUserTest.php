<?php

namespace Panda\Tests\Api\User;

use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PostUserTest extends ApiTestCase
{
    /** @test */
    function it_responses_with_no_content_for_created_user()
    {
        $this->request(HttpMethodEnum::POST, '/users', [
            'email' => 'panda@example.com',
            'password' => 'I<3BambooShoots',
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /** @test */
    function it_responses_with_no_content_for_already_created_user()
    {
        $this->loadFixturesFromFile('user.yaml');

        $this->request(HttpMethodEnum::POST, '/users', [
            'email' => 'panda@example.com',
            'password' => 'I<3BambooShoots',
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
}
