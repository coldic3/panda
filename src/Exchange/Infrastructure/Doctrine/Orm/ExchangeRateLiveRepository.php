<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Domain\Repository\ExchangeRateLiveRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class ExchangeRateLiveRepository extends DoctrineRepository implements ExchangeRateLiveRepositoryInterface
{
    private const ENTITY_CLASS = ExchangeRateLive::class;
    private const ALIAS = 'exchangeRateLive';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ExchangeRateLiveInterface $exchangeRate): void
    {
        $this->em->persist($exchangeRate);
    }

    public function remove(ExchangeRateLiveInterface $exchangeRate): void
    {
        $this->em->remove($exchangeRate);
    }

    public function findById(Uuid $id): ?ExchangeRateLiveInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findByBaseAndQuoteResources(string $baseTicker, string $quoteTicker): ?ExchangeRateLiveInterface
    {
        try {
            $result = $this->em
                ->createQueryBuilder()
                ->select('o')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.baseTicker = :baseTicker')
                ->andWhere('o.quoteTicker = :quoteTicker')
                ->setParameter('baseTicker', $baseTicker)
                ->setParameter('quoteTicker', $quoteTicker)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        Assert::isInstanceOf($result, ExchangeRateLiveInterface::class);

        return $result;
    }

    public function withBaseAndQuoteResourcesExist(string $baseTicker, string $quoteTicker): bool
    {
        try {
            return (bool) $this->em
                ->createQueryBuilder()
                ->select('COUNT(o.id)')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.baseTicker = :baseTicker')
                ->andWhere('o.quoteTicker = :quoteTicker')
                ->setParameter('baseTicker', $baseTicker)
                ->setParameter('quoteTicker', $quoteTicker)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            return false;
        }
    }

    public function defaultQuery(string $baseTicker = null, string $quoteTicker = null): QueryInterface
    {
        return new Query\DefaultExchangeRateLiveQuery($baseTicker, $quoteTicker);
    }
}
