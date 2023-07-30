<?php

declare(strict_types=1);

namespace Panda\Exchange\Application\Validation;

use Symfony\Component\Validator\Constraint;

final class ExchangeRateLogDatetimeOverlapped extends Constraint
{
    public const STARTED_AT_OVERLAPPED_ERROR = '45f824cf-cac4-4099-a3f0-51057e9d7d54';
    public const ENDED_AT_OVERLAPPED_ERROR = 'c734a168-052e-4ead-b6f8-d54896fda658';

    public string $message = 'The {{ datetime }} datetime cannot be overlapped with existing ExchangeRateLog identified by {{ existingLogId }}.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
