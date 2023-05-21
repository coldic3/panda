<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ValueObject;

final readonly class Resource implements ResourceInterface
{
    public function __construct(private string $ticker, private string $name)
    {
    }

    public function getTicker(): string
    {
        return $this->ticker;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
