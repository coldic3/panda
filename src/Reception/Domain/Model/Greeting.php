<?php

declare(strict_types=1);

namespace Panda\Reception\Domain\Model;

use Symfony\Component\Uid\Uuid;

final class Greeting
{
    public readonly Uuid $id;

    public function __construct(
        public string $name,
    ) {
        $this->id = Uuid::v4();
    }
}
