<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Query\Transaction;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Transaction\Domain\Repository\TransactionRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class FindTransactionsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(FindTransactionsQuery $query): ?CollectionIteratorInterface
    {
        Assert::isInstanceOf(
            $authorizedUser = $this->security->getUser(),
            OwnerInterface::class,
        );

        $transactionRepository = $this->transactionRepository->filterBy('owner', $authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $transactionRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $transactionRepository->collection();
    }
}
