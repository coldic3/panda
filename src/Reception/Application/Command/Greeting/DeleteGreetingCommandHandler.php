<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Command\Greeting;

use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;

final class DeleteGreetingCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly GreetingRepositoryInterface $greetingRepository)
    {
    }

    public function __invoke(DeleteGreetingCommand $command): void
    {
        $greeting = $this->greetingRepository->findById($command->id);

        if (null === $greeting) {
            return;
        }

        $this->greetingRepository->remove($greeting);
    }
}
