<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Account\Application\Command\User\CreateUserCommand;
use App\Account\Domain\Model\User;
use App\Account\Infrastructure\ApiResource\UserResource;
use App\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final class UserCreateProcesor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
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
