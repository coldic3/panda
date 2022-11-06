<?php

declare(strict_types=1);

namespace App\Reception\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Reception\Application\Query\Greeting\FindGreetingQuery;
use App\Reception\Application\Query\Greeting\FindGreetingsQuery;
use App\Reception\Domain\Model\Greeting;
use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Reception\Infrastructure\ApiResource\GreetingResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiState\Pagination\Paginator;
use App\Shared\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Symfony\Component\Uid\Uuid;

final class GreetingCrudProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    /**
     * @return GreetingResource|Paginator<GreetingResource>|array<GreetingResource>
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

        /** @var DoctrineCollectionIterator $models */
        $models = $this->queryBus->ask(
            new FindGreetingsQuery(
                $context['filters']['name'] ?? null,
                $offset,
                $limit,
            )
        );

        $resources = [];

        foreach ($models as $model) {
            $resources[] = GreetingResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?GreetingResource
    {
        /** @var Greeting|null $model */
        $model = $this->queryBus->ask(new FindGreetingQuery($id));

        return null !== $model ? GreetingResource::fromModel($model) : null;
    }
}
