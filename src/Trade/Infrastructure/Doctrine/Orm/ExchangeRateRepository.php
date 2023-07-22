<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRate;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Domain\Repository\ExchangeRateRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class ExchangeRateRepository extends DoctrineRepository implements ExchangeRateRepositoryInterface
{
    private const ENTITY_CLASS = ExchangeRate::class;
    private const ALIAS = 'exchangeRate';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ExchangeRateInterface $exchangeRate): void
    {
        $this->em->persist($exchangeRate);
    }

    public function remove(ExchangeRateInterface $exchangeRate): void
    {
        $this->em->remove($exchangeRate);
    }

    public function findById(Uuid $id): ?ExchangeRateInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findByBaseAndQuoteAssets(AssetInterface $baseAsset, AssetInterface $quoteAsset): ?ExchangeRateInterface
    {
        try {
            $result = $this->em
                ->createQueryBuilder()
                ->select('o')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.baseAsset = :baseAsset')
                ->andWhere('o.quoteAsset = :quoteAsset')
                ->setParameter('baseAsset', $baseAsset)
                ->setParameter('quoteAsset', $quoteAsset)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        Assert::isInstanceOf($result, ExchangeRateInterface::class);

        return $result;
    }

    public function withBaseAndQuoteAssetsExists(AssetInterface $baseAsset, AssetInterface $quoteAsset): bool
    {
        try {
            return (bool) $this->em
                ->createQueryBuilder()
                ->select('COUNT(o.id)')
                ->from(self::ENTITY_CLASS, 'o')
                ->where('o.baseAsset = :baseAsset')
                ->andWhere('o.quoteAsset = :quoteAsset')
                ->setParameter('baseAsset', $baseAsset)
                ->setParameter('quoteAsset', $quoteAsset)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            return false;
        }
    }

    public function defaultQuery(OwnerInterface $owner, string $ticker = null): QueryInterface
    {
        return new Query\DefaultExchangeRateQuery($owner, $ticker);
    }
}
