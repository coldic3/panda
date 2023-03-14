<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class DemoContext implements Context
{
    private ?Response $response = null;

    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    /**
     * @When wysyłam żądanie do :path
     */
    public function iSendARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then powinienem otrzymać odpowiedź
     */
    public function iShouldReceiveAResponse(): void
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }
}
