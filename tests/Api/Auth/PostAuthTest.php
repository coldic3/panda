<?php

namespace App\Tests\Api\Auth;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Account\Domain\Factory\UserFactoryInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Webmozart\Assert\Assert;

class PostAuthTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    /** @test */
    function it_generates_bearer_token_for_users_credentials()
    {
        $client = self::createClient();
        $container = self::getContainer();

        Assert::isInstanceOf(
            $doctrine = $container->get('doctrine'),
            Registry::class,
        );

        Assert::isInstanceOf(
            $userFactory = $container->get(UserFactoryInterface::class),
            UserFactoryInterface::class,
        );

        $user = $userFactory->create('panda@example.com', 'I<3BambooShoots');

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'panda@example.com',
                'password' => 'I<3BambooShoots',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/greetings');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/greetings', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }

    /** @test */
    function it_does_not_generate_bearer_token_for_invalid_credentials()
    {
        $client = self::createClient();
        $container = self::getContainer();

        Assert::isInstanceOf(
            $doctrine = $container->get('doctrine'),
            Registry::class,
        );

        Assert::isInstanceOf(
            $userFactory = $container->get(UserFactoryInterface::class),
            UserFactoryInterface::class,
        );

        $user = $userFactory->create('panda@example.com', 'I<3BambooShoots');

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'panda@example.com',
                'password' => 'IHateBambooShoots',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }
}
