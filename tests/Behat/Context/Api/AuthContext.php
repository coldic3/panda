<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Api;

use App\Tests\Util\HttpMethodEnum;
use App\Tests\Util\HttpRequestBuilder;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class AuthContext implements Context
{
    public function __construct(private readonly HttpRequestBuilder $http)
    {
    }

    /**
     * @When rozpoczynam autoryzację
     */
    public function i_start_authorization(): void
    {
        $this->http->initialize(HttpMethodEnum::POST, '/auth');
    }

    /**
     * @When podaję adres email :email
     */
    public function i_pass_an_email(string $email): void
    {
        $this->http->addToPayload('email', $email);
    }

    /**
     * @When podaję hasło :password
     */
    public function i_pass_a_password(string $password): void
    {
        $this->http->addToPayload('password', $password);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    public function i_submit_entered_data(): void
    {
        $this->http->finalize();
    }

    /**
     * @Then autoryzacja kończy się sukcesem
     */
    public function the_authorization_ends_with_a_success(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @Then autoryzacja kończy się niepowodzeniem
     */
    public function the_authorization_fails(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Then otrzymuję token autoryzacyjny
     */
    public function i_receive_authorization_token(): void
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::regex($response['token'] ?? '', '/^[\w-]+\.[\w-]+\.[\w-]+$/');
    }

    /**
     * @Then nie otrzymuję tokena autoryzacyjnego
     */
    public function i_do_not_receive_authorization_token(): void
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::keyNotExists($response, 'token');
    }
}
