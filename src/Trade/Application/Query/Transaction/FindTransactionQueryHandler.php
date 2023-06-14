<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Transaction;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Trade\Domain\Model\Transaction\TransactionInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;

final readonly class FindTransactionQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindTransactionQuery $query): ?TransactionInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $transaction = $this->transactionRepository->findById($query->id);

        if (null === $transaction) {
            return null;
        }

        $owner = $transaction->getOwnedBy();

        if (null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $transaction;
    }
}
