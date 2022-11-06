<?php

declare(strict_types=1);

namespace App\Reception\Application\Command\Greeting;

use App\Shared\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final class DeleteGreetingCommand implements CommandInterface
{
    public function __construct(public readonly Uuid $id)
    {
    }
}
