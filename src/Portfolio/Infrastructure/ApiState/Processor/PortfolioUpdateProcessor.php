<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Portfolio\Application\Command\Portfolio\UpdatePortfolioCommand;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Panda\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final readonly class PortfolioUpdateProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var PortfolioResource $data */
        Assert::isInstanceOf($data, PortfolioResource::class);

        $command = new UpdatePortfolioCommand($uriVariables['id'], (string) $data->name);

        /** @var PortfolioInterface $model */
        $model = $this->commandBus->dispatch($command);

        return PortfolioResource::fromModel($model);
    }
}
