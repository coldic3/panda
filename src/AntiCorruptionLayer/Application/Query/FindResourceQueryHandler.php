<?php

declare(strict_types=1);

namespace Panda\AntiCorruptionLayer\Application\Query;

use Panda\Asset\Application\Query\Asset\FindAssetQuery;
use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Application\Query\QueryBusInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;

final class FindResourceQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(FindResourceQuery $query): ?ResourceInterface
    {
        /** @var ResourceInterface|null $resource */
        $resource = $this->queryBus->ask(new FindAssetQuery($query->id));

        return $resource;
    }
}
