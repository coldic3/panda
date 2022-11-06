<?php

declare(strict_types=1);

namespace App\Reception\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Reception\Application\Command\Greeting\CreateGreetingCommand;
use App\Reception\Application\Command\Greeting\DeleteGreetingCommand;
use App\Reception\Application\Command\Greeting\UpdateGreetingCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Reception\Domain\Model\Greeting;
use App\Reception\Infrastructure\ApiResource\GreetingResource;
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
            ? new CreateGreetingCommand($data->name)
            : new UpdateGreetingCommand($uriVariables['id'], $data->name)
        ;

        /** @var Greeting $model */
        $model = $this->commandBus->dispatch($command);

        return GreetingResource::fromModel($model);
    }
}
