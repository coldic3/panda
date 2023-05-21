<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Account\Application\Command\User\CreateUserCommand;
use Panda\Account\Domain\Model\User;
use Panda\Account\Infrastructure\ApiResource\UserResource;
use Panda\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final readonly class UserCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        Assert::isInstanceOf($data, UserResource::class);

        $command = new CreateUserCommand((string) $data->email, (string) $data->password);

        /** @var User $model */
        $model = $this->commandBus->dispatch($command);

        return UserResource::fromModel($model);
    }
}
