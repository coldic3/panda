<?php

declare(strict_types=1);

namespace Panda\Report\Domain\ValueObject;

interface ReportFileInterface
{
    public function getStorage(): ?string;

    public function getFilename(): ?string;
}
