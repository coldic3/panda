<?php

declare(strict_types=1);

namespace Panda\Contract\AggregateRoot\Owner;

interface OwnershipInterface
{
    public function getOwnedBy(): ?OwnerInterface;

    public function setOwnedBy(OwnerInterface $owner): void;
}
