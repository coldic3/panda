<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLive;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;

final readonly class DeleteExchangeRateLiveCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ExchangeRateLiveRepositoryInterface $exchangeRateRepository)
    {
    }

    public function __invoke(DeleteExchangeRateLiveCommand $command): void
    {
        $exchangeRate = $this->exchangeRateRepository->findById($command->id);

        if (null === $exchangeRate) {
            return;
        }

        $this->exchangeRateRepository->remove($exchangeRate);
    }
}
