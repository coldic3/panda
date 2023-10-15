<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\ValueObject;

readonly class ReportFile implements ReportFileInterface
{
    public const LOCAL_STORAGE = 'local';

    public function __construct(private ?string $storage, private ?string $filename)
    {
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }
}
