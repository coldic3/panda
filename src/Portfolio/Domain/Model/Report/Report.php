<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Model\Report;

use Panda\Core\Domain\Model\TimestampableTrait;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\ValueObject\ReportEntryInterface;
use Panda\Portfolio\Domain\ValueObject\ReportFileInterface;
use Symfony\Component\Uid\Uuid;

final class Report implements ReportInterface
{
    use TimestampableTrait;

    private Uuid $id;
    private ?\DateTimeInterface $startedAt = null;
    private ?\DateTimeInterface $endedAt = null;

    public function __construct(
        private string $name,
        private ReportEntryInterface $entry,
        private ReportFileInterface $file,
        private PortfolioInterface $portfolio,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEntry(): ReportEntryInterface
    {
        return $this->entry;
    }

    public function getFile(): ReportFileInterface
    {
        return $this->file;
    }

    public function setFile(ReportFileInterface $file): void
    {
        $this->file = $file;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeInterface $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function getPortfolio(): PortfolioInterface
    {
        return $this->portfolio;
    }
}
