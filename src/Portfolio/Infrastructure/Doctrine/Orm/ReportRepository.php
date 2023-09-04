<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Portfolio\Domain\Model\Report\Report;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class ReportRepository extends DoctrineRepository implements ReportRepositoryInterface
{
    private const ENTITY_CLASS = Report::class;
    private const ALIAS = 'report';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ReportInterface $report): void
    {
        $this->em->persist($report);
    }

    public function remove(ReportInterface $report): void
    {
        $this->em->remove($report);
    }

    public function findById(Uuid $id): ?ReportInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function defaultQuery(OwnerInterface $owner): QueryInterface
    {
        return new Query\DefaultReportQuery($owner);
    }
}
