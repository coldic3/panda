<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiState\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Application\Command\Report\CreateReportCommand;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Infrastructure\ApiResource\ReportResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ReportCreateProcessor implements ProcessorInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var ReportResource $data */
        Assert::isInstanceOf($data, ReportResource::class);

        Assert::isInstanceOf(
            $portfolioId = $data->portfolio?->id,
            Uuid::class,
        );

        $command = new CreateReportCommand(
            (string) $data->name,
            (string) $data->entry?->type,
            (array) $data->entry?->configuration,
            (string) $data->file?->storage,
            (string) $data->file?->filename,
            $portfolioId,
        );

        /** @var ReportInterface $model */
        $model = $this->commandBus->dispatch($command);

        return ReportResource::fromModel($model);
    }
}
