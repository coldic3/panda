<?php

declare(strict_types=1);

namespace Panda\Tests\Util;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class HttpRequestBuilder
{
    private HttpMethodEnum $httpMethod;
    private string $httpPath;
    private array $httpPayload = [];
    private ?string $authToken = null;
    private ResponseInterface $httpResponse;

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function initialize(HttpMethodEnum $httpMethod, string $httpPath, ?string $authToken = null): void
    {
        $this->httpMethod = $httpMethod;
        $this->httpPath = $httpPath;
        $this->authToken = $authToken;
    }

    public function addToPayload(string $key, mixed $value): void
    {
        $this->httpPayload[$key] = $value;
    }

    public function getPayloadElement(string $key): mixed
    {
        return $this->httpPayload[$key] ?? null;
    }

    public function finalize(): void
    {
        $headers = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json',
        ];

        if (null !== $this->authToken) {
            $headers['Authorization'] = sprintf('Bearer %s', $this->authToken);
        }

        $this->httpResponse = $this->httpClient->request(
            $this->httpMethod->value,
            $this->httpPath,
            [
                'json' => $this->httpPayload,
                'headers' => $headers,
            ]
        );
    }

    public function getResponse(): ResponseInterface
    {
        return $this->httpResponse;
    }
}
