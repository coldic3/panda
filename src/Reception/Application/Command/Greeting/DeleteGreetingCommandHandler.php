<?php

declare(strict_types=1);

namespace App\Reception\Application\Command\Greeting;

use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

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
