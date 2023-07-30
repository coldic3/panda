<?php

declare(strict_types=1);

namespace Panda\Tests\Fixture\Factory;

use Panda\Exchange\Domain\Model\ExchangeRateLog;

final class ExchangeRateLogFactory
{
    public static function createWorkingDayLog(
        string $baseTicker,
        string $quoteTicker,
        float $rate,
        string $firstDate,
        int|string $workingDay,
    ): ExchangeRateLog {
        $workingDay = (int) $workingDay;
        $dayOfWeek = (int) date('N', strtotime($firstDate));

        if ($dayOfWeek > 5) {
            throw new \InvalidArgumentException('First date must be a working day.');
        }

        if ((($dayOfWeek + $workingDay) % 6) === 0) {
            // skip Saturday and Sunday
            $workingDay = $workingDay + 2;
        } elseif ((($dayOfWeek + $workingDay) % 7) === 0) {
            // skip Sunday
            $workingDay = $workingDay + 1;
        }

        return new ExchangeRateLog(
            $baseTicker,
            $quoteTicker,
            $rate,
            new \DateTimeImmutable(sprintf('%s 00:00:00 +%d days', $firstDate, $workingDay)),
            new \DateTimeImmutable(sprintf('%s 23:59:59 +%d days', $firstDate, $workingDay)),
        );
    }
}
