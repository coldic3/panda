<?php

declare(strict_types=1);

namespace Panda\AccountOHS\Domain\Provider;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;

interface AuthorizedUserProviderInterface
{
    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function provide(): OwnerInterface;
}
