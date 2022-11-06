<?php

declare(strict_types=1);

namespace App\Reception\Application\Command\Greeting;

use App\Reception\Domain\Model\Greeting;
use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

final class CreateGreetingCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly GreetingRepositoryInterface $greetingRepository)
    {
    }

    public function __invoke(CreateGreetingCommand $command): Greeting
    {
        $greeting = new Greeting($command->name);

        $greeting->name = $command->name;

        $this->greetingRepository->save($greeting);

        return $greeting;
    }
}
