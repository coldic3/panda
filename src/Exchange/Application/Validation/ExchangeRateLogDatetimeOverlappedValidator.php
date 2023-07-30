<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Validation;

use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ExchangeRateLogDatetimeOverlappedValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ExchangeRateLogRepositoryInterface $exchangeRateLogRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExchangeRateLogDatetimeOverlapped) {
            throw new UnexpectedTypeException($constraint, ExchangeRateLogDatetimeOverlapped::class);
        }

        if (!$value instanceof ExchangeRateLogInterface) {
            throw new UnexpectedValueException($value, ExchangeRateLogInterface::class);
        }

        $existingExchangeRateLog = $this->exchangeRateLogRepository->findInDatetimeRange(
            $value->getBaseTicker(),
            $value->getQuoteTicker(),
            $value->getStartedAt(),
            $value->getEndedAt(),
        );
        if (null !== $existingExchangeRateLog) {
            if (
                $value->getStartedAt() >= $existingExchangeRateLog->getStartedAt()
                && $value->getStartedAt() <= $existingExchangeRateLog->getEndedAt()
            ) {
                $this->buildStartedAtValidation($constraint, $value, $existingExchangeRateLog);

                return;
            }

            if (
                $value->getEndedAt() >= $existingExchangeRateLog->getStartedAt()
                && $value->getEndedAt() <= $existingExchangeRateLog->getEndedAt()
            ) {
                $this->buildEndedAtValidation($constraint, $value, $existingExchangeRateLog);

                return;
            }

            $this->buildStartedAtValidation($constraint, $value, $existingExchangeRateLog);
            $this->buildEndedAtValidation($constraint, $value, $existingExchangeRateLog);
        }
    }

    private function buildValidation(
        ExchangeRateLogDatetimeOverlapped $constraint,
        string $field,
        string $code,
        \DateTimeInterface $datetime,
        ExchangeRateLogInterface $existingLog,
    ): void {
        $this->context->buildViolation($constraint->message)
            ->atPath($field)
            ->setCode($code)
            ->setParameter('{{ datetime }}', $this->formatValue($datetime->format('Y-m-d H:i:s')))
            ->setParameter('{{ existingLogId }}', $this->formatValue($existingLog->getId()->toRfc4122()))
            ->addViolation();
    }

    private function buildStartedAtValidation(
        ExchangeRateLogDatetimeOverlapped $constraint,
        ExchangeRateLogInterface $newLog,
        ExchangeRateLogInterface $existingLog,
    ): void {
        $this->buildValidation(
            $constraint,
            'startedAt',
            ExchangeRateLogDatetimeOverlapped::STARTED_AT_OVERLAPPED_ERROR,
            $newLog->getStartedAt(),
            $existingLog,
        );
    }

    private function buildEndedAtValidation(
        ExchangeRateLogDatetimeOverlapped $constraint,
        ExchangeRateLogInterface $newLog,
        ExchangeRateLogInterface $existingLog,
    ): void {
        $this->buildValidation(
            $constraint,
            'endedAt',
            ExchangeRateLogDatetimeOverlapped::ENDED_AT_OVERLAPPED_ERROR,
            $newLog->getEndedAt(),
            $existingLog,
        );
    }
}
