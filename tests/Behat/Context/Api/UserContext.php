<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @When tworzę nowe konto
     */
    function i_create_a_new_account()
    {
        $this->http->initialize(HttpMethodEnum::POST, '/users');
    }

    /**
     * @When wyświetlam informacje o koncie :email
     */
    function i_show_account_information(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf('/users/%s', $user->getId()),
            $this->clipboard->paste('token')
        );

        $this->http->finalize();
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
     * @When nie podaję żadnych danych
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then rejestracja kończy się sukcesem
     */
    function the_registration_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NO_CONTENT);
    }

    /**
     * @Then rejestracja kończy się niepowodzeniem
     */
    function the_registration_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Then nie widzę żadnych dodatkowych informacji
     */
    function i_can_not_see_any_additional_information()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::isEmpty($response);
    }

    /**
     * @Then konto zostało utworzone w systemie
     * @Then kolejne konto nie zostało utworzone w systemie
     */
    function the_account_has_been_created()
    {
        Assert::notNull($this->userRepository->findByEmail($this->http->getPayloadElement('email')));
    }

    /**
     * @Then konto nie zostało utworzone w systemie
     */
    function the_account_has_not_been_created()
    {
        Assert::null($this->userRepository->findByEmail($this->http->getPayloadElement('email')));
    }

    /**
     * @Then widzę pojedynczy komunikat o niepoprawnym adresie email
     * @Then widzę pojedynczy komunikat o pustym adresie email
     */
    function i_see_a_single_entry_about_an_invalid_email_address()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'email');
        Assert::same($actualViolatedPropertiesWithCount['email'], 1);
    }

    /**
     * @Then widzę pojedynczy komunikat o niepoprawnym haśle
     * @Then widzę pojedynczy komunikat o pustym haśle
     */
    function i_see_a_single_entry_about_an_invalid_password()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'password');
        Assert::same($actualViolatedPropertiesWithCount['password'], 1);
    }

    /**
     * @Then informacje o użytkowniku zostają wyświetlone
     */
    function the_user_information_are_showed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::notEmpty($this->http->getResponse()->getContent());
    }

    /**
     * @Then informacje o użytkowniku nie zostają wyświetlone
     */
    function the_user_information_are_not_showed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }
}
