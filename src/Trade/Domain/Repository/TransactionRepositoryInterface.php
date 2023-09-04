<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Repository;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Domain\Repository\RepositoryInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Symfony\Component\Uid\Uuid;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function save(TransactionInterface $transaction): void;

    public function remove(TransactionInterface $transaction): void;

    public function findById(Uuid $id): ?TransactionInterface;

    public function defaultQuery(
        OwnerInterface $owner,
        string $fromOperationAssetId = null,
        string $toOperationAssetId = null,
        \DateTimeImmutable $afterConcludedAt = null,
        \DateTimeImmutable $beforeConcludedAt = null,
        bool $afterConcludedAtInclusive = false,
        bool $beforeConcludedAtInclusive = false,
    ): QueryInterface;
}
