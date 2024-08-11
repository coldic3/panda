<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLogRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class ExchangeRateLogRepository extends DoctrineRepository implements ExchangeRateLogRepositoryInterface
{
    private const ENTITY_CLASS = ExchangeRateLog::class;
    private const ALIAS = 'exchangeRateLog';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ExchangeRateLogInterface $exchangeRateLog): void
    {
        $this->em->persist($exchangeRateLog);
    }

    public function remove(ExchangeRateLogInterface $exchangeRateLog): void
    {
        $this->em->remove($exchangeRateLog);
    }

    public function findById(Uuid $id): ?ExchangeRateLogInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findInDatetimeRange(
        OwnerInterface $owner,
        string $baseTicker,
        string $quoteTicker,
        \DateTimeInterface $fromDatetime,
        \DateTimeInterface $toDatetime,
    ): ?ExchangeRateLogInterface {
        $queryBuilder = $this->em->createQueryBuilder();

        try {
            $result = $queryBuilder
                ->select('o')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.owner = :owner')
                ->andWhere('o.baseTicker = :baseTicker AND o.quoteTicker = :quoteTicker')
                ->andWhere($queryBuilder->expr()->orX(
                    ':fromDatetime >= o.startedAt AND :fromDatetime <= o.endedAt',
                    ':toDatetime >= o.startedAt AND :toDatetime <= o.endedAt',
                    ':fromDatetime <= o.startedAt AND :toDatetime >= o.endedAt',
                ))
                ->setParameter('owner', $owner)
                ->setParameter('baseTicker', $baseTicker)
                ->setParameter('quoteTicker', $quoteTicker)
                ->setParameter('fromDatetime', $fromDatetime)
                ->setParameter('toDatetime', $toDatetime)
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        Assert::isInstanceOf($result, ExchangeRateLogInterface::class);

        return $result;
    }

    public function findByDatetime(
        OwnerInterface $owner,
        string $baseTicker,
        string $quoteTicker,
        \DateTimeInterface $datetime,
    ): ?ExchangeRateLogInterface {
        $queryBuilder = $this->em->createQueryBuilder();

        try {
            $result = $queryBuilder
                ->select('o')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.owner = :owner')
                ->andWhere('o.baseTicker = :baseTicker AND o.quoteTicker = :quoteTicker')
                ->andWhere(':datetime >= o.startedAt AND :datetime <= o.endedAt')
                ->setParameter('owner', $owner)
                ->setParameter('baseTicker', $baseTicker)
                ->setParameter('quoteTicker', $quoteTicker)
                ->setParameter('datetime', $datetime)
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        Assert::isInstanceOf($result, ExchangeRateLogInterface::class);

        return $result;
    }

    public function defaultQuery(
        OwnerInterface $owner,
        ?string $baseTicker = null,
        ?string $quoteTicker = null,
        ?\DateTimeInterface $fromDatetime = null,
        ?\DateTimeInterface $toDatetime = null
    ): QueryInterface {
        return new Query\DefaultExchangeRateLogQuery($owner, $baseTicker, $quoteTicker, $fromDatetime, $toDatetime);
    }
}
