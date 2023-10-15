<?php

declare(strict_types=1);

namespace Panda\Report\Application\Query\Report;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Core\Domain\Repository\CollectionIteratorInterface;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;

final readonly class FindReportsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindReportsQuery $query): ?CollectionIteratorInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();

        $reportQuery = $this->reportRepository->defaultQuery($authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->reportRepository->pagination($reportQuery, $query->page, $query->itemsPerPage);
        }

        return $this->reportRepository->collection($reportQuery);
    }
}
