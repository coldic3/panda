<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Shared\Domain\Repository\SortDirection;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Trade\Domain\Model\Transaction\Transaction;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class TransactionRepository extends DoctrineRepository implements TransactionRepositoryInterface
{
    private const ENTITY_CLASS = Transaction::class;
    private const ALIAS = 'transaction';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TransactionInterface $transaction): void
    {
        $this->em->persist($transaction);
    }

    public function remove(TransactionInterface $transaction): void
    {
        $this->em->remove($transaction);
    }

    public function findById(Uuid $id): ?TransactionInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function buildComplexQuery(
        OwnerInterface $owner,
        ?string $fromOperationAssetId = null,
        ?string $toOperationAssetId = null,
        ?\DateTimeImmutable $afterConcludedAt = null,
        ?\DateTimeImmutable $beforeConcludedAt = null,
    ): TransactionRepositoryInterface {
        return $this->buildOnto(static function (QueryBuilder $qb) use (
            $owner, $fromOperationAssetId, $toOperationAssetId, $afterConcludedAt, $beforeConcludedAt
        ): void {
            $queryBuilder = $qb
                ->addSelect([
                    'fromOperation',
                    'fromOperationAsset',
                    'toOperation',
                    'toOperationAsset',
                ])
                ->leftJoin('transaction.fromOperation', 'fromOperation')
                ->leftJoin('fromOperation.asset', 'fromOperationAsset')
                ->leftJoin('transaction.toOperation', 'toOperation')
                ->leftJoin('toOperation.asset', 'toOperationAsset')
                ->andWhere('transaction.owner = :owner')
                ->addOrderBy('transaction.concludedAt', SortDirection::DESC->value)
                ->setParameter('owner', $owner);

            if (null !== $fromOperationAssetId) {
                $queryBuilder
                    ->andWhere('fromOperationAsset.id = :fromOperationAssetId')
                    ->setParameter('fromOperationAssetId', $fromOperationAssetId);
            }

            if (null !== $toOperationAssetId) {
                $queryBuilder
                    ->andWhere('toOperationAsset.id = :toOperationAssetId')
                    ->setParameter('toOperationAssetId', $toOperationAssetId);
            }

            if (null !== $toOperationAssetId) {
                $queryBuilder
                    ->andWhere('toOperationAsset.id = :toOperationAssetId')
                    ->setParameter('toOperationAssetId', $toOperationAssetId);
            }

            if (null !== $afterConcludedAt) {
                $queryBuilder
                    ->andWhere('transaction.concludedAt > :afterConcludedAt')
                    ->setParameter(
                        'afterConcludedAt',
                        $afterConcludedAt->format(\DateTimeInterface::ATOM)
                    );
            }

            if (null !== $beforeConcludedAt) {
                $queryBuilder
                    ->andWhere('transaction.concludedAt < :beforeConcludedAt')
                    ->setParameter(
                        'beforeConcludedAt',
                        $beforeConcludedAt->format(\DateTimeInterface::ATOM)
                    );
            }
        });
    }

    protected function getEntityClass(): string
    {
        return self::ENTITY_CLASS;
    }

    protected function getAlias(): string
    {
        return self::ALIAS;
    }
}
