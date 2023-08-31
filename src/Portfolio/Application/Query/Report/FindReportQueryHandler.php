<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Query\Report;

use Panda\AccountOHS\Domain\Exception\AuthorizedUserNotFoundExceptionInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Core\Application\Query\QueryHandlerInterface;
use Panda\Portfolio\Domain\Model\Report\ReportInterface;
use Panda\Portfolio\Domain\Repository\ReportRepositoryInterface;

final readonly class FindReportQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private AuthorizedUserProviderInterface $authorizedUserProvider,
    ) {
    }

    /**
     * @throws AuthorizedUserNotFoundExceptionInterface
     */
    public function __invoke(FindReportQuery $query): ?ReportInterface
    {
        $authorizedUser = $this->authorizedUserProvider->provide();
        $report = $this->reportRepository->findById($query->id);

        if (null === $report) {
            return null;
        }

        $owner = $report->getPortfolio()->getOwnedBy();

        if (null === $owner || !$owner->compare($authorizedUser)) {
            return null;
        }

        return $report;
    }
}
