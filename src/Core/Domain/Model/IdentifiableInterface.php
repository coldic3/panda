<?php

declare(strict_types=1);

namespace Panda\Core\Domain\Model;

use Symfony\Component\Uid\Uuid;

interface IdentifiableInterface
{
    public function getId(): Uuid;
}
