<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Core\Infrastructure\ApiState\Pagination\Paginator;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLiveQuery;
use Panda\Exchange\Application\Query\ExchangeRateLive\FindExchangeRateLivesQuery;
use Panda\Exchange\Domain\Model\ExchangeRateLive;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateLiveResource;
use Symfony\Component\Uid\Uuid;

final readonly class ExchangeRateLiveProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    /**
     * @return ExchangeRateLiveResource|Paginator<ExchangeRateLiveResource>|array<ExchangeRateLiveResource>
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

        $baseTicker = isset($context['filters']['baseTicker'])
            ? (string) $context['filters']['baseTicker']
            : null;
        $quoteTicker = isset($context['filters']['quoteTicker'])
            ? (string) $context['filters']['quoteTicker']
            : null;

        /** @var DoctrineCollectionIterator<ExchangeRateLive> $models */
        $models = $this->queryBus->ask(new FindExchangeRateLivesQuery(
            $baseTicker,
            $quoteTicker,
            $offset,
            $limit,
        ));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = ExchangeRateLiveResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?ExchangeRateLiveResource
    {
        /** @var ExchangeRateLive|null $model */
        $model = $this->queryBus->ask(new FindExchangeRateLiveQuery($id));

        return null !== $model ? ExchangeRateLiveResource::fromModel($model) : null;
    }
}
