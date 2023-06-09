<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Hook;

use Behat\Behat\Context\Context;

final class ClipboardContext implements Context
{
    private array $clipboard = [];

    public function copy(string $key, mixed $value): void
    {
        $this->clipboard[$key] = $value;
    }

    public function paste(string $key): mixed
    {
        return $this->clipboard[$key] ?? null;
    }
}
