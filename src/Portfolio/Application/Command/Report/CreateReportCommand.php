<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Report;

use Symfony\Component\Uid\Uuid;

final readonly class CreateReportCommand
{
    /** @param array<string, mixed> $entryConfiguration */
    public function __construct(
        public string $name,
        public string $entryType,
        public array $entryConfiguration,
        public string $fileStorage,
        public string $fileName,
        public Uuid $portfolioId,
    ) {
    }
}
