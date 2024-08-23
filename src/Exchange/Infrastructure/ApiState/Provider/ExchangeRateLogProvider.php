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
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogQuery;
use Panda\Exchange\Application\Query\ExchangeRateLog\FindExchangeRateLogsQuery;
use Panda\Exchange\Domain\Model\ExchangeRateLog;
use Panda\Exchange\Infrastructure\ApiResource\ExchangeRateLogResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ExchangeRateLogProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    /**
     * @return ExchangeRateLogResource|Paginator<ExchangeRateLogResource>|array<ExchangeRateLogResource>
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

        /** @phpstan-ignore-next-line false positive */
        $baseTicker = isset($context['filters']['baseTicker'])
            /** @phpstan-ignore-next-line false positive */
            ? (string) $context['filters']['baseTicker']
            : null;
        /** @phpstan-ignore-next-line false positive */
        $quoteTicker = isset($context['filters']['quoteTicker'])
            /** @phpstan-ignore-next-line false positive */
            ? (string) $context['filters']['quoteTicker']
            : null;

        /** @var DoctrineCollectionIterator<ExchangeRateLog> $models */
        $models = $this->queryBus->ask(new FindExchangeRateLogsQuery(
            $baseTicker,
            $quoteTicker,
            $offset,
            $limit,
        ));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = ExchangeRateLogResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?ExchangeRateLogResource
    {
        /** @var ExchangeRateLog|null $model */
        $model = $this->queryBus->ask(new FindExchangeRateLogQuery($id));

        return null !== $model ? ExchangeRateLogResource::fromModel($model) : null;
    }
}
