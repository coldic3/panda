<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Account\Application\Query\User\FindUserQuery;
use App\Account\Domain\Model\User;
use App\Account\Infrastructure\ApiResource\UserResource;
use App\Shared\Application\Query\QueryBusInterface;

final class UserProvider implements ProviderInterface
{
    public function __construct(private readonly QueryBusInterface $queryBus)
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
