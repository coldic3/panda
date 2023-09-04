<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Repository;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Symfony\Component\Uid\Uuid;

interface ReportRepositoryInterface extends RepositoryInterface
{
    public function save(ReportInterface $report): void;

    public function remove(ReportInterface $report): void;

    public function findById(Uuid $id): ?ReportInterface;

    public function defaultQuery(OwnerInterface $owner): QueryInterface;
}
