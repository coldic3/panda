<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Portfolio\Domain\ReportGenerator\AllocationReportGenerator;
use Panda\Portfolio\Domain\ReportGenerator\PerformanceReportGenerator;
use Panda\Portfolio\Domain\ValueObject\ReportEntryInterface;
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
