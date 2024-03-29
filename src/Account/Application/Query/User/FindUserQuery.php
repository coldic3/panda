<?php

declare(strict_types=1);

namespace Panda\Account\Application\Query\User;

use Panda\Core\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class FindUserQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
