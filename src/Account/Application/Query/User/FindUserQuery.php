<?php

declare(strict_types=1);

namespace App\Account\Application\Query\User;

use App\Shared\Application\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class FindUserQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id,
    ) {
    }
}
