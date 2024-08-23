<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Panda\Report\Application\Action\DownloadReportAction;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Infrastructure\ApiState\Processor\ReportCreateProcessor;
use Panda\Report\Infrastructure\ApiState\Provider\ReportProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Report',
    operations: [
        new GetCollection(),
        new Get(),
        new Get(
            uriTemplate: '/reports/{id}/download',
            formats: ['csv' => ['text/csv']],
            controller: DownloadReportAction::class,
            openapi: new OpenApiOperation(
                responses: [
                    '200' => [
                        'description' => 'Report file',
                        'content' => [
                            'text/csv' => [
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'binary',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Downloads a report.',
                description: 'Downloads a report.',
            ),
        ),
        new Post(
            validationContext: ['groups' => ['create']],
            processor: ReportCreateProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP], 'skip_null_values' => false],
    denormalizationContext: ['groups' => [self::CREATE_GROUP]],
    provider: ReportProvider::class,
)]
final class ReportResource
{
    public const READ_GROUP = 'report:read';
    public const CREATE_GROUP = 'report:create';

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?string $name = null,

        #[ApiProperty(genId: false)]
        #[Groups([ReportResource::READ_GROUP, self::CREATE_GROUP])]
        public ?ReportEntryRepresentation $entry = null,

        #[ApiProperty(writable: false, genId: false)]
        #[Groups([ReportResource::READ_GROUP])]
        public ?ReportFileRepresentation $file = null,

        #[ApiProperty]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?PortfolioResource $portfolio = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $startedAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $endedAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $createdAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }

    public static function fromModel(ReportInterface $report): ReportResource
    {
        return new self(
            $report->getId(),
            $report->getName(),
            ReportEntryRepresentation::fromValueObject($report->getEntry()),
            ReportFileRepresentation::fromValueObject($report->getFile()),
            PortfolioResource::fromModel($report->getPortfolio()),
            $report->getStartedAt(),
            $report->getEndedAt(),
            $report->getCreatedAt(),
            $report->getUpdatedAt(),
        );
    }
}
