<?php

declare(strict_types=1);

namespace Panda\Shared\Domain\Repository;

enum SortDirectionEnum: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
