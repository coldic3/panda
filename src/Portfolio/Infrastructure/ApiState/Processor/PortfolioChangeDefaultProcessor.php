<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangeDefaultPortfolioCommand;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class PortfolioChangeDefaultProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): PortfolioResource
    {
        /** @var PortfolioResource $data */
        Assert::isInstanceOf($data, PortfolioResource::class);
        Assert::isInstanceOf($id = $uriVariables['id'] ?? null, Uuid::class);

        $command = new ChangeDefaultPortfolioCommand($id);

        /** @var PortfolioInterface $model */
        $model = $this->commandBus->dispatch($command);

        return PortfolioResource::fromModel($model);
    }
}
