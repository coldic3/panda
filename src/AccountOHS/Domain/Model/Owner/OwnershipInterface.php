<?php

declare(strict_types=1);

namespace Panda\AccountOHS\Domain\Model\Owner;

interface OwnershipInterface
{
    public function getOwnedBy(): ?OwnerInterface;

    public function setOwnedBy(OwnerInterface $owner): void;
}
