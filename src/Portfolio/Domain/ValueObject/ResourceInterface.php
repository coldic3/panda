<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ValueObject;

interface ResourceInterface
{
    public function getTicker(): string;

    public function getName(): string;
}
