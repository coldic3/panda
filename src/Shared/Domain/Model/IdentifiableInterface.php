<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Model;

interface IdentifiableInterface
{
    public function getId(): mixed;
}
