<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLog;

use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;

final readonly class DeleteExchangeRateLogCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository)
    {
    }

    public function __invoke(DeleteExchangeRateLogCommand $command): void
    {
        $exchangeRateLog = $this->exchangeRateLogRepository->findById($command->id);

        if (null === $exchangeRateLog) {
            return;
        }

        $this->exchangeRateLogRepository->remove($exchangeRateLog);
    }
}
