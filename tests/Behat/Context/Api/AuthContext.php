<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
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
    function i_start_authorization()
    {
        $this->http->initialize(HttpMethodEnum::POST, '/auth');
    }

    /**
     * @When podaję adres email :email
     */
    function i_pass_an_email(string $email)
    {
        $this->http->addToPayload('email', $email);
    }

    /**
     * @When podaję hasło :password
     */
    function i_pass_a_password(string $password)
    {
        $this->http->addToPayload('password', $password);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then autoryzacja kończy się sukcesem
     */
    function the_authorization_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @Then autoryzacja kończy się niepowodzeniem
     */
    function the_authorization_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Then otrzymuję token autoryzacyjny
     */
    function i_receive_authorization_token()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::regex($response['token'] ?? '', '/^[\w-]+\.[\w-]+\.[\w-]+$/');
    }

    /**
     * @Then nie otrzymuję tokena autoryzacyjnego
     */
    function i_do_not_receive_authorization_token()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::keyNotExists($response, 'token');
    }
}
