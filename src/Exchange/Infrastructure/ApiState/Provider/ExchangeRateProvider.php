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
use Panda\Exchange\Application\Query\ExchangeRate\FindExchangeRateQuery;
use Panda\Exchange\Application\Query\ExchangeRate\FindExchangeRatesQuery;
use Panda\Exchange\Domain\Model\ExchangeRate;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateResource;
use Symfony\Component\Uid\Uuid;

final readonly class ExchangeRateProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    /**
     * @return ExchangeRateResource|Paginator<ExchangeRateResource>|array<ExchangeRateResource>
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

        $baseResourceTicker = isset($context['filters']['baseResourceTicker'])
            ? (string) $context['filters']['baseResourceTicker']
            : null;
        $quoteResourceTicker = isset($context['filters']['quoteResourceTicker'])
            ? (string) $context['filters']['quoteResourceTicker']
            : null;

        /** @var DoctrineCollectionIterator<ExchangeRate> $models */
        $models = $this->queryBus->ask(new FindExchangeRatesQuery(
            $baseResourceTicker,
            $quoteResourceTicker,
            $offset,
            $limit,
        ));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = ExchangeRateResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?ExchangeRateResource
    {
        /** @var ExchangeRate|null $model */
        $model = $this->queryBus->ask(new FindExchangeRateQuery($id));

        return null !== $model ? ExchangeRateResource::fromModel($model) : null;
    }
}
