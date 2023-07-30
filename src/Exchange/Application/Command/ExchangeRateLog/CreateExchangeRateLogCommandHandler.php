<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Command\ExchangeRateLog;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Exchange\Domain\Factory\ExchangeRateLogFactoryInterface;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;

final readonly class CreateExchangeRateLogCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExchangeRateLogRepositoryInterface $exchangeRateLogRepository,
        private ExchangeRateLogFactoryInterface $exchangeRateLogFactory,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateExchangeRateLogCommand $command): ExchangeRateLogInterface
    {
        $exchangeRateLog = $this->exchangeRateLogFactory->create(
            $command->baseTicker,
            $command->quoteTicker,
            $command->rate,
            $command->startedAt,
            $command->endedAt,
        );

        $this->validator->validate($exchangeRateLog, ['groups' => ['panda:create']]);

        $this->exchangeRateLogRepository->save($exchangeRateLog);

        return $exchangeRateLog;
    }
}
