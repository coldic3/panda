<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Report\Application\ReportGenerator\AllocationReportGenerator;
use Panda\Report\Application\ReportGenerator\PerformanceReportGenerator;
use Panda\Report\Domain\ValueObject\ReportEntryInterface;
use Symfony\Component\Serializer\Annotation\Groups;

final class ReportEntryRepresentation
{
    /** @param array<string, mixed> $configuration */
    public function __construct(
        #[ApiProperty(openapiContext: ['enum' => [PerformanceReportGenerator::TYPE, AllocationReportGenerator::TYPE]])]
        #[Groups([ReportResource::READ_GROUP, ReportResource::CREATE_GROUP])]
        public ?string $type = null,

        #[ApiProperty]
        #[Groups([ReportResource::READ_GROUP, ReportResource::CREATE_GROUP])]
        public ?array $configuration = null,
    ) {
    }

    public static function fromValueObject(ReportEntryInterface $entry): ReportEntryRepresentation
    {
        return new self(
            $entry->getType(),
            $entry->getConfiguration(),
        );
    }
}
