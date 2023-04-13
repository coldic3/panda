<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Query\Transaction;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Transaction\Domain\Model\TransactionInterface;
use Panda\Transaction\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class FindTransactionQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(FindTransactionQuery $query): ?TransactionInterface
    {
        $transaction = $this->transactionRepository->findById($query->id);

        if (null === $transaction) {
            return null;
        }

        /** @var OwnerInterface|null $authorizedUser */
        $authorizedUser = $this->security->getUser();
        $owner = $transaction->getOwnedBy();

        if (null === $authorizedUser || null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $transaction;
    }
}
