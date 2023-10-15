<?php

declare(strict_types=1);

namespace Panda\Report\Domain\Model\Report;

use Panda\Core\Domain\Model\IdentifiableInterface;
use Panda\Core\Domain\Model\TimestampableInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\ValueObject\ReportEntryInterface;
use Panda\Report\Domain\ValueObject\ReportFileInterface;

interface ReportInterface extends IdentifiableInterface, TimestampableInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getEntry(): ReportEntryInterface;

    public function getFile(): ReportFileInterface;

    public function setFile(ReportFileInterface $file): void;

    public function getStartedAt(): ?\DateTimeInterface;

    public function setStartedAt(\DateTimeInterface $startedAt): void;

    public function getEndedAt(): ?\DateTimeInterface;

    public function setEndedAt(\DateTimeInterface $endedAt): void;

    public function getPortfolio(): PortfolioInterface;
}
