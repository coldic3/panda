<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Core\Infrastructure\ApiState\Pagination\Paginator;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Panda\Report\Application\Query\Report\FindReportQuery;
use Panda\Report\Application\Query\Report\FindReportsQuery;
use Panda\Report\Domain\Model\Report\Report;
use Panda\Report\Infrastructure\ApiResource\ReportResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ReportProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    /**
     * @return ReportResource|Paginator<ReportResource>|array<ReportResource>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            Assert::isInstanceOf($id = $uriVariables['id'] ?? null, Uuid::class);

            return $this->provideItem($id);
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var DoctrineCollectionIterator<Report> $models */
        $models = $this->queryBus->ask(new FindReportsQuery($offset, $limit));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = ReportResource::fromModel($model);
        }

        if (null !== $paginator = $models->paginator()) {
            $resources = new Paginator(
                $resources,
                $paginator->getCurrentPage(),
                $paginator->getItemsPerPage(),
                $paginator->getLastPage(),
                $paginator->getTotalItems(),
            );
        }

        return $resources;
    }

    private function provideItem(Uuid $id): ?ReportResource
    {
        /** @var Report|null $model */
        $model = $this->queryBus->ask(new FindReportQuery($id));

        return null !== $model ? ReportResource::fromModel($model) : null;
    }
}
