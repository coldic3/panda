<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiState\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Panda\Core\Application\Query\QueryBusInterface;
use Panda\Trade\Application\Query\ExchangeRate\FindExchangeRateByAssetIdsQuery;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRate;
use Panda\Trade\Infrastructure\ApiResource\ExchangeRateResource;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class ExchangeRateBaseQuoteProvider implements ProviderInterface
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?ExchangeRateResource
    {
        Assert::isInstanceOf($operation, Get::class);
        Assert::isInstanceOf($uriVariables['baseAssetId'] ?? null, Uuid::class);
        Assert::isInstanceOf($uriVariables['quoteAssetId'] ?? null, Uuid::class);

        /** @var ExchangeRate|null $model */
        $model = $this->queryBus->ask(new FindExchangeRateByAssetIdsQuery(
            $uriVariables['baseAssetId'],
            $uriVariables['quoteAssetId'],
        ));

        if (null !== $model) {
            return ExchangeRateResource::fromModel($model);
        }

        return null;
    }
}
