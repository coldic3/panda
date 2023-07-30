<?php

declare(strict_types=1);

namespace Panda\Exchange\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Model\TimestampableTrait;

class ExchangeRateLive extends ExchangeRate implements ExchangeRateLiveInterface
{
    use TimestampableTrait;

    private ?OwnerInterface $owner = null;

    public function getOwnedBy(): ?OwnerInterface
    {
        return $this->owner;
    }

    public function setOwnedBy(OwnerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
