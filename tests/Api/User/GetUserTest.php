<?php

namespace Panda\Tests\Api\User;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class GetUserTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $uri = sprintf('/users/%s', $user->id);

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_authorized_user()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $uri = sprintf('/users/%s', $user->id);

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'user/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_does_not_get_other_user()
    {
        $fixtures = $this->loadFixturesFromFiles(['user.yaml', 'users.yaml']);

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var User $otherUser */
        $otherUser = $fixtures['user_1'];

        $uri = sprintf('/users/%s', $otherUser->id);

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /** @test */
    function it_does_not_get_a_user_with_invalid_id()
    {
        $fixtures = $this->loadFixturesFromFile('user.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $uri = sprintf('/users/%s', 'd7b9b815-1304-4a94-8332-bf3a218831c1');

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
