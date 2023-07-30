<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLog\CreateExchangeRateLogCommand;

class ExchangeRateLogContext implements Context
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    /**
     * @Given kurs dla pary :baseQuote w dniu :datetime wyniósł :rate
     */
    function the_exchange_rate_for_pair_in_day_is(array $baseQuote, \DateTimeImmutable $datetime, float $rate)
    {
        $this->commandBus->dispatch(new CreateExchangeRateLogCommand(
            $baseQuote['base'],
            $baseQuote['quote'],
            $rate,
            new \DateTimeImmutable($datetime->format('Y-m-d 00:00:00')),
            new \DateTimeImmutable($datetime->format('Y-m-d 23:59:59')),
        ));
    }
}
