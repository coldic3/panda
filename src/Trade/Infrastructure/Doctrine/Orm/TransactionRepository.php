<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
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

    public function defaultQuery(
        OwnerInterface $owner,
        string $fromOperationAssetId = null,
        string $toOperationAssetId = null,
        \DateTimeImmutable $afterConcludedAt = null,
        \DateTimeImmutable $beforeConcludedAt = null,
        bool $afterConcludedAtInclusive = false,
        bool $beforeConcludedAtInclusive = false,
    ): QueryInterface {
        return new Query\DefaultTransactionQuery(
            $owner,
            $fromOperationAssetId,
            $toOperationAssetId,
            $afterConcludedAt,
            $beforeConcludedAt,
            $afterConcludedAtInclusive,
            $beforeConcludedAtInclusive,
        );
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
