<?php

declare(strict_types=1);

namespace Panda\Reception\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Reception\Application\Command\Greeting\CreateGreetingCommand;
use Panda\Reception\Application\Command\Greeting\DeleteGreetingCommand;
use Panda\Reception\Application\Command\Greeting\UpdateGreetingCommand;
use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Infrastructure\ApiResource\GreetingResource;
use Panda\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final class GreetingCrudProcesor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        Assert::isInstanceOf($data, GreetingResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteGreetingCommand($uriVariables['id']));

            return null;
        }

        $command = !isset($uriVariables['id'])
            ? new CreateGreetingCommand((string) $data->name)
            : new UpdateGreetingCommand($uriVariables['id'], $data->name)
        ;

        /** @var Greeting $model */
        $model = $this->commandBus->dispatch($command);

        return GreetingResource::fromModel($model);
    }
}
