<?php

declare(strict_types=1);

namespace Panda\Core\Domain\Model;

interface TimestampableInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;

    public function getUpdatedAt(): ?\DateTimeInterface;
}
