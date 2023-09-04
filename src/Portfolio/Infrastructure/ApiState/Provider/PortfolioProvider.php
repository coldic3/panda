<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Core\Infrastructure\ApiState\Pagination\Paginator;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Panda\Portfolio\Application\Query\Portfolio\FindPortfolioQuery;
use Panda\Portfolio\Application\Query\Portfolio\FindPortfoliosQuery;
use Panda\Portfolio\Domain\Model\Portfolio\Portfolio;
use Panda\Portfolio\Infrastructure\ApiResource\PortfolioResource;
use Symfony\Component\Uid\Uuid;

final readonly class PortfolioProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    /**
     * @return PortfolioResource|Paginator<PortfolioResource>|array<PortfolioResource>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            return $this->provideItem($uriVariables['id']);
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var DoctrineCollectionIterator<Portfolio> $models */
        $models = $this->queryBus->ask(new FindPortfoliosQuery($offset, $limit));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = PortfolioResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?PortfolioResource
    {
        /** @var Portfolio|null $model */
        $model = $this->queryBus->ask(new FindPortfolioQuery($id));

        return null !== $model ? PortfolioResource::fromModel($model) : null;
    }
}
