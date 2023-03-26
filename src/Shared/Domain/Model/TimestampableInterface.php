<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Model;

interface TimestampableInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;

    public function getUpdatedAt(): ?\DateTimeInterface;
}
