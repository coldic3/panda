<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Query\Transaction;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;

final class FindTransactionsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindTransactionsQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $transactionRepository = $this->transactionRepository->filterBy('owner', $authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $transactionRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $transactionRepository->collection();
    }
}
