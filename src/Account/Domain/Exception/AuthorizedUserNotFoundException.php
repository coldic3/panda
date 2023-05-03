<?php

declare(strict_types=1);

namespace Panda\Account\Domain\Exception;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;

final class AuthorizedUserNotFoundException extends \Exception implements AuthorizedUserNotFoundExceptionInterface
{
    protected $message = 'Authorized user not found';
}
