<?php

declare(strict_types=1);

namespace Panda\Core\Application\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}
