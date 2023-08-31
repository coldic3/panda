<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ValueObject;

final readonly class NullReportFile implements ReportFileInterface
{
    public function getStorage(): null
    {
        return null;
    }

    public function getFilename(): null
    {
        return null;
    }
}
