<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Panda\Account\Application\Query\User\FindUserQuery;
use Panda\Account\Domain\Model\User;
use Panda\Account\Infrastructure\ApiResource\UserResource;
use Panda\Shared\Application\Query\QueryBusInterface;

final readonly class UserProvider implements ProviderInterface
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserResource
    {
        /** @var User|null $model */
        $model = $this->queryBus->ask(new FindUserQuery($uriVariables['id']));

        if (null === $model) {
            return null;
        }

        return UserResource::fromModel($model);
    }
}
