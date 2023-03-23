<?php

declare(strict_types=1);

namespace Panda\Asset\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Panda\Asset\Application\Query\Asset\FindAssetQuery;
use Panda\Asset\Application\Query\Asset\FindAssetsQuery;
use Panda\Asset\Domain\Model\Asset;
use Panda\Asset\Infrastructure\ApiResource\AssetResource;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Infrastructure\ApiState\Pagination\Paginator;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineCollectionIterator;
use Symfony\Component\Uid\Uuid;

final class AssetProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    /**
     * @return AssetResource|Paginator<AssetResource>|array<AssetResource>
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

        /** @var DoctrineCollectionIterator<Asset> $models */
        $models = $this->queryBus->ask(new FindAssetsQuery($offset, $limit));

        $resources = [];

        foreach ($models as $model) {
            $resources[] = AssetResource::fromModel($model);
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

    private function provideItem(Uuid $id): ?AssetResource
    {
        /** @var Asset|null $model */
        $model = $this->queryBus->ask(new FindAssetQuery($id));

        return null !== $model ? AssetResource::fromModel($model) : null;
    }
}
