<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangeDefaultPortfolioCommand;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Webmozart\Assert\Assert;

final readonly class PortfolioChangeDefaultProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var PortfolioResource $data */
        Assert::isInstanceOf($data, PortfolioResource::class);

        $command = new ChangeDefaultPortfolioCommand($uriVariables['id']);

        /** @var PortfolioInterface $model */
        $model = $this->commandBus->dispatch($command);

        return PortfolioResource::fromModel($model);
    }
}
