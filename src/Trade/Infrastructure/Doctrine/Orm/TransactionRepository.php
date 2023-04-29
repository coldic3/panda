<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
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

    protected function getEntityClass(): string
    {
        return self::ENTITY_CLASS;
    }

    protected function getAlias(): string
    {
        return self::ALIAS;
    }
}
