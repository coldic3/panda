<?php

declare(strict_types=1);

namespace Panda\Report\Domain\ValueObject;

readonly class NullReportFile extends ReportFile
{
    public function __construct()
    {
        parent::__construct(null, null);
    }

    public function getStorage(): null
    {
        return null;
    }

    public function getFilename(): null
    {
        return null;
    }
}
