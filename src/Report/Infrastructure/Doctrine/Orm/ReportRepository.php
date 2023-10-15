<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Report\Domain\Model\Report\Report;
use Panda\Report\Domain\Model\Report\ReportInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
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
        return new \Panda\Report\Infrastructure\Doctrine\Orm\Query\DefaultReportQuery($owner);
    }
}
