<?php

declare(strict_types=1);

namespace Panda\Contract\AggregateRoot\Resource;

use Panda\Shared\Domain\Model\IdentifiableInterface;

interface ResourceInterface extends IdentifiableInterface
{
    public function compare(ResourceInterface $resource): bool;
}
