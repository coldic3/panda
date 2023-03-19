<?php

declare(strict_types=1);

namespace Panda\Reception\Application\Command\Greeting;

use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;
use Webmozart\Assert\Assert;

final class UpdateGreetingCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly GreetingRepositoryInterface $greetingRepository)
    {
    }

    public function __invoke(UpdateGreetingCommand $command): Greeting
    {
        $greeting = $this->greetingRepository->findById($command->id);
        Assert::notNull($greeting);

        $greeting->name = $command->name ?? $greeting->name;

        $this->greetingRepository->save($greeting);

        return $greeting;
    }
}
