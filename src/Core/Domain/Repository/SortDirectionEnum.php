<?php

declare(strict_types=1);

namespace Panda\Core\Domain\Repository;

enum SortDirectionEnum: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
