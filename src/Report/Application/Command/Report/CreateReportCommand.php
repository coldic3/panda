<?php

declare(strict_types=1);

namespace Panda\Report\Application\Command\Report;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class CreateReportCommand implements CommandInterface
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
