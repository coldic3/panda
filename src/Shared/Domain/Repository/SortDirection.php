<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

enum SortDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
