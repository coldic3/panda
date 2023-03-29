<?php

declare(strict_types=1);

namespace Panda\Contract\AggregateRoot\Owner;

use Panda\Shared\Domain\Model\IdentifiableInterface;

interface OwnerInterface extends IdentifiableInterface
{
    public function compare(OwnerInterface $owner): bool;
}
