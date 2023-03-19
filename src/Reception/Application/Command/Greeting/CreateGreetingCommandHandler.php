<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Command\Greeting;

use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

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
