<?php

declare(strict_types=1);

namespace Panda\Account\Application\Query\User;

use Panda\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindUserQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
