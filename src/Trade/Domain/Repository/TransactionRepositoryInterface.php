<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Repository;

use Panda\Shared\Domain\Repository\RepositoryInterface;
use Panda\Trade\Domain\Model\TransactionInterface;
use Symfony\Component\Uid\Uuid;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function save(TransactionInterface $transaction): void;

    public function remove(TransactionInterface $transaction): void;

    public function findById(Uuid $id): ?TransactionInterface;
}
