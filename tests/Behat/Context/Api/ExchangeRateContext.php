<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Exception\ItemNotFoundException;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class ExchangeRateContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly IriConverterInterface $iriConverter,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @When tworzę nowy kurs wymiany
     */
    function i_create_a_new_exchange_rate()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/exchange_rate_lives',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When /^modyfikuję kurs wymiany dla pary ("([^"]+\/[^"]+)")$/
     */
    function i_edit_the_exchange_rate(ExchangeRateLiveInterface $exchangeRateLive)
    {
        $this->http->initialize(
            HttpMethodEnum::PATCH,
            sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId()),
            $this->clipboard->paste('token'),
        );
    }

    /**
     * @When /^usuwam kurs wymiany dla pary ("([^"]+\/[^"]+)")$/
     */
    function i_delete_exchange_rate(ExchangeRateLiveInterface $exchangeRateLive)
    {
        $this->http->initialize(
            HttpMethodEnum::DELETE,
            sprintf('/exchange_rate_lives/%s', $exchangeRateLive->getId()),
            $this->clipboard->paste('token'),
        );

        $this->http->finalize();
    }

    /**
     * @When wybieram ticker bazowy :ticker
     */
    function i_enter_base_ticker(string $ticker)
    {
        $this->http->addToPayload('baseTicker', $ticker);
    }

    /**
     * @When wybieram ticker kwotowany :ticker
     */
    function i_enter_quote_ticker(string $ticker)
    {
        $this->http->addToPayload('quoteTicker', $ticker);
    }

    /**
     * @When wybieram kurs :rate
     * @When podaję nowy kurs :rate
     */
    function i_enter_rate(float $rate)
    {
        $this->http->addToPayload('rate', $rate);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @When wyświetlam kurs wymiany dla pary :baseQuote
     */
    function there_is_an_exchange_rate(array $baseQuote)
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf(
                '/exchange_rate_lives?baseTicker=%s&quoteTicker=%s',
                $baseQuote['base'],
                $baseQuote['quote']
            ),
            $this->clipboard->paste('token'),
        );

        $this->http->finalize();
    }

    /**
     * @Then informacje o kursie wymiany zostają wyświetlane
     */
    function the_exchange_rate_information_is_displayed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::notEmpty($this->http->getResponse()->getContent());
    }

    /**
     * @Then informacje o kursie wymiany nie zostają wyświetlane
     */
    function the_exchange_rate_information_is_not_displayed()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
        Assert::notEmpty($this->http->getResponse()->getContent(false));
    }

    /**
     * @Then widzę, że kurs wymiany wynosi :rate
     */
    function i_see_exchange_rate_is(float $rate)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        if (isset($response['hydra:member'][0])) {
            $response = $response['hydra:member'][0];
        }

        Assert::keyExists($response, 'rate');
        Assert::same((float) $response['rate'], $rate);
    }

    /**
     * @Then dodawanie kursu wymiany kończy się sukcesem
     */
    function the_exchange_rate_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_CREATED);
    }

    /**
     * @Then usuwanie kursu wymiany kończy się sukcesem
     */
    function i_see_exchange_rate_deletion_is_successful()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NO_CONTENT);
    }

    /**
     * @Then edycja kursu wymiany kończy się sukcesem
     */
    function i_see_exchange_rate_edition_is_successful()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * @Then dodawanie kursu wymiany kończy się niepowodzeniem
     */
    function the_exchange_rate_creation_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Then widzę pojedynczy komunikat, że kurs wymiany już istnieje
     */
    function i_see_a_single_entry_that_exchange_rate_already_exists()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'baseTicker');
        Assert::same($actualViolatedPropertiesWithCount['baseTicker'], 1);
    }

    /**
     * @Then kurs wymiany został dodany do listy kursów wymiany
     */
    function the_exchange_rate_has_been_added()
    {
        $response = $this->http->getResponse()->toArray();

        Assert::notNull(
            $this->entityManager->getRepository(ExchangeRateLive::class)->findOneBy([
                'baseTicker' => $response['baseTicker'],
                'quoteTicker' => $response['quoteTicker'],
                'rate' => $response['rate'],
            ])
        );
    }

    /**
     * @Then odwrotny kurs wymiany w wysokości :rate został dodany do listy kursów wymiany
     * @Then odwrotny kurs wymiany został uaktualniony i wynosi :rate
     */
    function reversed_exchange_rate_has_been_added(float $rate)
    {
        $response = $this->http->getResponse()->toArray();

        Assert::notNull(
            $this->entityManager->getRepository(ExchangeRateLive::class)->findOneBy([
                'baseTicker' => $response['quoteTicker'],
                'quoteTicker' => $response['baseTicker'],
                'rate' => $rate,
            ])
        );
    }

    /**
     * @Then kurs wymiany został usunięty z listy kursów wymiany
     */
    function the_exchange_rate_has_been_deleted()
    {
        Assert::throws(
            fn () => $this->iriConverter->getResourceFromIri($this->http->getPath()),
            ItemNotFoundException::class,
        );
    }
}
