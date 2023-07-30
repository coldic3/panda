<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class ExchangeRateLogContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly IriConverterInterface $iriConverter,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @When dodaję historyczny kurs
     */
    function i_create_a_new_exchange_rate_log()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/exchange_rate_logs',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When usuwam kurs wymiany dla pary :baseQuote w dniu :datetime
     */
    function i_delete_exchange_rate(array $baseQuote, \DateTimeImmutable $datetime)
    {
        /** @var ExchangeRateLog|null $exchangeRateLog */
        $exchangeRateLog = $this->entityManager->getRepository(ExchangeRateLog::class)->findOneBy([
            'baseTicker' => $baseQuote['base'],
            'quoteTicker' => $baseQuote['quote'],
            'startedAt' => new \DateTimeImmutable($datetime->format('Y-m-d 00:00:00')),
            'endedAt' => new \DateTimeImmutable($datetime->format('Y-m-d 23:59:59')),
        ]);

        Assert::notNull($exchangeRateLog);

        $this->http->initialize(
            HttpMethodEnum::DELETE,
            sprintf('/exchange_rate_logs/%s', $exchangeRateLog->getId()),
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
     */
    function i_enter_rate(float $rate)
    {
        $this->http->addToPayload('rate', $rate);
    }

    /**
     * @When wybieram datę oraz czas od kiedy obowiązuje kurs :datetime
     */
    function i_enter_started_at(\DateTimeImmutable $datetime)
    {
        $this->http->addToPayload('startedAt', $datetime->format('Y-m-d H:i:s'));
    }

    /**
     * @When wybieram datę oraz czas do kiedy obowiązuje kurs :datetime
     */
    function i_enter_ended_at(\DateTimeImmutable $datetime)
    {
        $this->http->addToPayload('endedAt', $datetime->format('Y-m-d H:i:s'));
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @When wyświetlam historyczne kursy dla pary :baseQuote
     */
    function there_is_an_exchange_rate(array $baseQuote)
    {
        $this->http->initialize(
            HttpMethodEnum::GET,
            sprintf(
                '/exchange_rate_logs?baseTicker=%s&quoteTicker=%s',
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
     * @Then dodawanie historycznego kursu kończy się sukcesem
     */
    function the_exchange_rate_log_creation_ends_with_a_success()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_CREATED);
    }

    /**
     * @Then kurs wymiany został usunięty z listy historycznych kursów
     * @Then usuwanie kursu wymiany kończy się sukcesem
     */
    function the_exchange_rate_log_has_been_deleted()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_NO_CONTENT);
    }

    /**
     * @Then dodawanie historycznego kursu kończy się niepowodzeniem
     */
    function the_exchange_rate_log_creation_fails()
    {
        Assert::same($this->http->getResponse()->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Then widzę pojedynczy komunikat, że dla podanej daty rozpoczęcia obowiązywania kursu istnieje już kurs
     */
    function i_see_a_single_entry_that_exchange_rate_log_for_a_given_started_at_datetime_already_exists()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'startedAt');
        Assert::same($actualViolatedPropertiesWithCount['startedAt'], 1);
    }

    /**
     * @Then widzę pojedynczy komunikat, że dla podanej daty zakończenia obowiązywania kursu istnieje już kurs
     */
    function i_see_a_single_entry_that_exchange_rate_log_for_a_given_ended_at_datetime_already_exists()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'endedAt');
        Assert::same($actualViolatedPropertiesWithCount['endedAt'], 1);
    }

    /**
     * @Then widzę pojedynczy komunikat, że data zakończenia obowiązywania kursu nie może być mniejsza niż data rozpoczęcia
     */
    function i_see_a_single_entry_that_exchange_rate_log_cannot_has_started_at_greater_than_ended_at()
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $violations = $response['violations'] ?? [];
        $actualViolatedPropertiesWithCount = array_count_values(array_column($violations, 'propertyPath'));

        Assert::keyExists($actualViolatedPropertiesWithCount, 'endedAt');
        Assert::same($actualViolatedPropertiesWithCount['endedAt'], 1);
    }

    /**
     * @Then kurs został dodany do listy historycznych kursów
     */
    function the_exchange_rate_log_has_been_added()
    {
        $response = $this->http->getResponse()->toArray();

        Assert::notNull(
            $this->entityManager->getRepository(ExchangeRateLog::class)->findOneBy([
                'baseTicker' => $response['baseTicker'],
                'quoteTicker' => $response['quoteTicker'],
                'rate' => $response['rate'],
                'startedAt' => new \DateTimeImmutable($response['startedAt']),
                'endedAt' => new \DateTimeImmutable($response['endedAt']),
            ])
        );
    }

    /**
     * @Then /^widzę (\d+) historycznych kursów$/
     */
    function i_see_number_of_exchange_rate_logs(int $count)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);

        Assert::same($response['hydra:totalItems'], $count);
    }

    /**
     * @Then na pierwszej pozycji jest kurs z dnia :datetime o wartości :rate
     */
    function at_first_position_there_is_an_exchange_rate_log(\DateTimeImmutable $datetime, float $rate)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $item = $response['hydra:member'][0];

        Assert::same((new \DateTimeImmutable($item['startedAt']))->format('Y-m-d H:i:s'), $datetime->format('Y-m-d 00:00:00'));
        Assert::same((new \DateTimeImmutable($item['endedAt']))->format('Y-m-d H:i:s'), $datetime->format('Y-m-d 23:59:59'));
        Assert::same($item['rate'], $rate);
    }

    /**
     * @Then na ostatniej pozycji jest kurs z dnia :datetime o wartości :rate
     */
    function at_last_position_there_is_an_exchange_rate_log(\DateTimeImmutable $datetime, float $rate)
    {
        $response = json_decode($this->http->getResponse()->getContent(false), true);
        $item = $response['hydra:member'][count($response['hydra:member']) - 1];

        Assert::same((new \DateTimeImmutable($item['startedAt']))->format('Y-m-d H:i:s'), $datetime->format('Y-m-d 00:00:00'));
        Assert::same((new \DateTimeImmutable($item['endedAt']))->format('Y-m-d H:i:s'), $datetime->format('Y-m-d 23:59:59'));
        Assert::same($item['rate'], $rate);
    }
}
