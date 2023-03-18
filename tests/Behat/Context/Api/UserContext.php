<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Api;

use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Tests\Behat\Context\Setup\UserContext as SetupUserContext;
use App\Tests\Util\HttpMethodEnum;
use App\Tests\Util\HttpRequestBuilder;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    private SetupUserContext $setupUserContext;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->setupUserContext = $environment->getContext(SetupUserContext::class);
    }

    /**
     * @When tworzę nowe konto
     */
    public function i_create_a_new_account(): void
    {
        $this->http->initialize(HttpMethodEnum::POST, '/users');
    }

    /**
     * @When wyświetlam informacje o koncie :email
     */
    public function i_show_account_information(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf('/users/%s', $user->id),
            $this->setupUserContext->token
        );

        $this->http->finalize();
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
     * @When nie podaję żadnych danych
     */
    public function i_submit_entered_data(): void
    {
        $this->http->finalize();
    }

    /**
     * @Then rejestracja kończy się sukcesem
     */
    public function the_registration_ends_with_a_success(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NO_CONTENT);
    }

    /**
     * @Then rejestracja kończy się niepowodzeniem
     */
    public function the_registration_fails(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Then nie widzę żadnych dodatkowych informacji
     */
    public function i_can_not_see_any_additional_information(): void
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::isEmpty($response);
    }

    /**
     * @Then konto zostało utworzone w systemie
     * @Then kolejne konto nie zostało utworzone w systemie
     */
    public function the_account_has_been_created(): void
    {
        Assert::notNull($this->userRepository->findByEmail($this->http->getPayloadElement('email')));
    }

    /**
     * @Then konto nie zostało utworzone w systemie
     */
    public function the_account_has_not_been_created(): void
    {
        Assert::null($this->userRepository->findByEmail($this->http->getPayloadElement('email')));
    }

    /**
     * @Then widzę pojedynczy komunikat o niepoprawnym adresie email
     * @Then widzę pojedynczy komunikat o pustym adresie email
     */
    public function i_see_a_single_entry_about_an_invalid_email_address(): void
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
    public function i_see_a_single_entry_about_an_invalid_password(): void
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
    public function the_user_information_are_showed(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::notEmpty($this->http->getResponse()->getContent());
    }

    /**
     * @Then informacje o użytkowniku nie zostają wyświetlone
     */
    public function the_user_information_are_not_showed(): void
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }
}
