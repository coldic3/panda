<?php

namespace App\Tests\Api\User;

use App\Tests\Util\ApiTestCase;
use App\Tests\Util\HttpMethodEnum;
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

    /** @test */
    function it_validates_request_body()
    {
        $this->request(HttpMethodEnum::POST, '/users', [
            'email' => 'panda',
            'password' => 'bamboo',
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseViolationsPropertyPaths($response, [
            'email' => 1,
            'password' => 1,
        ]);
        $this->assertResponseCode($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function it_requires_all_parameters()
    {
        $this->request(HttpMethodEnum::POST, '/users', []);

        $response = $this->client->getResponse();

        $this->assertResponseViolationsPropertyPaths($response, [
            'email' => 1,
            'password' => 1,
        ]);
        $this->assertResponseCode($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
