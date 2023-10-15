<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Report\Domain\ValueObject\ReportFileInterface;
use Symfony\Component\Serializer\Annotation\Groups;

final class ReportFileRepresentation
{
    public function __construct(
        #[ApiProperty]
        #[Groups([ReportResource::READ_GROUP, ReportResource::CREATE_GROUP])]
        public ?string $storage = null,

        #[ApiProperty]
        #[Groups([ReportResource::READ_GROUP, ReportResource::CREATE_GROUP])]
        public ?string $filename = null,
    ) {
    }

    public static function fromValueObject(ReportFileInterface $file): ?ReportFileRepresentation
    {
        if (null === $file->getStorage() || null === $file->getFilename()) {
            return null;
        }

        return new self(
            $file->getStorage(),
            $file->getFilename(),
        );
    }
}
