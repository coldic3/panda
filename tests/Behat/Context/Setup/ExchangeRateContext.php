<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Exchange\Application\Command\ExchangeRateLive\CreateExchangeRateLiveCommand;

class ExchangeRateContext implements Context
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    /**
     * @Given istnieje kurs wymiany :baseQuote na poziomie :rate
     */
    function there_is_an_exchange_rate(array $baseQuote, float $rate)
    {
        $this->commandBus->dispatch(new CreateExchangeRateLiveCommand($baseQuote['base'], $baseQuote['quote'], $rate));
    }
}
