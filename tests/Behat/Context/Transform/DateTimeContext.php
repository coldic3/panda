<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;

class DateTimeContext implements Context
{
    /**
     * @Transform :datetime
     * @Transform :fromDatetime
     * @Transform :toDatetime
     */
    public function datetime(string $datetime): \DateTimeImmutable
    {
        return new \DateTimeImmutable($datetime);
    }
}
