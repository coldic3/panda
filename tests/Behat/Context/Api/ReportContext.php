<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Api;

use ApiPlatform\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Tests\Util\HttpRequestBuilder;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class ReportContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly HttpRequestBuilder $http,
        private readonly IriConverterInterface $iriConverter,
        private readonly string $projectDir,
    ) {
    }

    /**
     * @When konfiguruję raport
     */
    function i_create_a_new_report()
    {
        $this->http->initialize(
            HttpMethodEnum::POST,
            '/reports',
            $this->clipboard->paste('token')
        );
    }

    /**
     * @When podaję nazwę raportu :name
     */
    function i_specify_report_name(string $name)
    {
        $this->http->addToPayload('name', $name);
    }

    /**
     * @When /^podaję (portfel "[^"]+")$/
     */
    function i_specify_portfolio(PortfolioInterface $portfolio)
    {
        $this->http->addToPayload(
            'portfolio',
            $this->iriConverter->getIriFromResource(PortfolioResource::fromModel($portfolio)),
        );
    }

    /**
     * @When podaję typ raportu :type
     */
    function i_specify_report_entry_type(string $type)
    {
        $entry = $this->http->getPayloadElement('entry') ?? [];

        $entry['type'] = $type;

        $this->http->addToPayload('entry', $entry);
    }

    /**
     * @When podaję przedział dat od :fromDatetime do :toDatetime
     */
    function i_specify_report_entry_configuration_datetime_range(\DateTimeImmutable $fromDatetime, \DateTimeImmutable $toDatetime)
    {
        $entry = $this->http->getPayloadElement('entry') ?? [];

        $entry['configuration'] = [
            'fromDatetime' => $fromDatetime->format('Y-m-d H:i:s'),
            'toDatetime' => $toDatetime->format('Y-m-d H:i:s'),
        ];

        $this->http->addToPayload('entry', $entry);
    }

    /**
     * @When podaję datę :datetime
     */
    function i_specify_report_entry_configuration_datetime(\DateTimeImmutable $datetime)
    {
        $entry = $this->http->getPayloadElement('entry') ?? [];

        $entry['configuration'] = [
            'datetime' => $datetime->format('Y-m-d H:i:s'),
        ];

        $this->http->addToPayload('entry', $entry);
    }

    /**
     * @When zatwierdzam wprowadzone dane
     */
    function i_submit_entered_data()
    {
        $this->http->finalize();
    }

    /**
     * @Then otrzymuję raport wydajności
     * @Then otrzymuję raport udziału aktywów
     */
    function the_report_creation_ends_with_a_success()
    {
        $response = $this->http->getResponse();
        $content = json_decode($response->getContent(false), true);

        Assert::same($response->getStatusCode(), Response::HTTP_CREATED);
        Assert::same($content['file']['storage'] ?? null, 'local');
        Assert::endsWith($content['file']['filename'] ?? null, '.csv');
        Assert::notNull($content['startedAt'] ?? null);
        Assert::notNull($content['endedAt'] ?? null);
    }

    /**
     * @Then raport wydajności zawiera:
     */
    function the_performance_report_contains(TableNode $table)
    {
        $response = $this->http->getResponse();
        $content = json_decode($response->getContent(false), true);
        $reportFilename = $content['file']['filename'];

        $csvFilePath = sprintf('%s/private/reports/%s', $this->projectDir, $reportFilename);

        $handle = fopen($csvFilePath, 'r');
        Assert::notFalse($handle);

        Assert::same(fgetcsv($handle), ['initial value', 'final value', 'profit/loss', 'rate of return']);

        foreach ($table as $row) {
            $csvRow = fgetcsv($handle);

            Assert::same($row['initial value'], $csvRow[0]);
            Assert::same($row['final value'], $csvRow[1]);
            Assert::same($row['profit/loss'], $csvRow[2]);
            Assert::same($row['rate of return'], $csvRow[3]);
        }

        fclose($handle);
    }

    /**
     * @Then raport udziału aktywów zawiera:
     */
    function the_allocation_report_contains(TableNode $table)
    {
        $response = $this->http->getResponse();
        $content = json_decode($response->getContent(false), true);
        $reportFilename = $content['file']['filename'];

        $csvFilePath = sprintf('%s/private/reports/%s', $this->projectDir, $reportFilename);

        $handle = fopen($csvFilePath, 'r');
        Assert::notFalse($handle);

        Assert::same(fgetcsv($handle), ['ticker', 'quantity', 'value', 'share']);

        foreach ($table as $row) {
            $csvRow = fgetcsv($handle);

            Assert::same($row['ticker'], $csvRow[0]);
            Assert::same($row['quantity'], $csvRow[1]);
            Assert::same($row['value'], $csvRow[2]);
            Assert::same($row['share'], $csvRow[3]);
        }

        fclose($handle);
    }
}
