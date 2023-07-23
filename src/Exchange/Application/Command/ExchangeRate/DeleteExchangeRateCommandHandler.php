<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRate;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateRepositoryInterface;

final readonly class DeleteExchangeRateCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(DeleteExchangeRateCommand $command): void
    {
        $exchangeRate = $this->exchangeRateRepository->findById($command->id);

        if (null === $exchangeRate) {
            return;
        }

        $this->exchangeRateRepository->remove($exchangeRate);
    }
}
