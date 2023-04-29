<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Infrastructure\ApiState\Pagination\Paginator;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Panda\Trade\Application\Query\Transaction\FindTransactionQuery;
use Panda\Trade\Application\Query\Transaction\FindTransactionsQuery;
use Panda\Trade\Domain\Model\Transaction\Transaction;
use Panda\Trade\Infrastructure\ApiResource\TransactionResource;
use Symfony\Component\Uid\Uuid;

final class TransactionProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    /**
     * @return TransactionResource|Paginator<TransactionResource>|array<TransactionResource>
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

        /** @var DoctrineCollectionIterator<Transaction> $models */
        $models = $this->queryBus->ask(new FindTransactionsQuery($offset, $limit));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = TransactionResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?TransactionResource
    {
        /** @var Transaction|null $model */
        $model = $this->queryBus->ask(new FindTransactionQuery($id));

        return null !== $model ? TransactionResource::fromModel($model) : null;
    }
}
