<?php

declare(strict_types=1);

namespace Panda\Report\Application\Provider;

use Panda\Report\Domain\ValueObject\ReportFile;

final readonly class ReportFilePathProvider implements ReportFilePathProviderInterface
{
    public function __construct(private string $storageDir)
    {
    }

    public function provide(ReportFile $reportFile): string
    {
        if (ReportFile::LOCAL_STORAGE !== $reportFile->getStorage()) {
            throw new \InvalidArgumentException(sprintf('Unsupported storage type: %s', $reportFile->getStorage()));
        }

        return sprintf('%s/%s', $this->storageDir, $reportFile->getFilename());
    }
}
