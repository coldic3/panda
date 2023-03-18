<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiTestCase\JsonApiTestCase;
use App\Account\Domain\Model\UserInterface;
use App\Tests\Util\HttpMethodEnum;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends JsonApiTestCase
{
    protected function request(HttpMethodEnum $method, string $uri, array $body = [], array $headers = []): void
    {
        $this->client->request(
            method: $method->value,
            uri: $uri,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ] + $headers,
            content: json_encode($body),
        );
    }

    protected function generateBearerToken(UserInterface $user): string
    {
        /** @var JWTEncoderInterface $encoder */
        $encoder = $this->getContainer()->get(JWTEncoderInterface::class);

        $jwt = $encoder->encode([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);

        return sprintf('Bearer %s', $jwt);
    }

    protected function generateAuthorizationHeader(UserInterface $user): array
    {
        return ['HTTP_Authorization' => $this->generateBearerToken($user)];
    }

    protected function assertResponseNoContent(Response $response): void
    {
        $this->assertEmpty($response->getContent());
    }

    protected function assertUnauthorized(Response $response): void
    {
        $this->assertResponse($response, '_common/unauthorized', Response::HTTP_UNAUTHORIZED);
    }
}
