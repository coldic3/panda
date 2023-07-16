<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Panda\Trade\Domain\Model\ExchangeRate\ExchangeRateInterface;
use Panda\Trade\Infrastructure\ApiState\Processor\ExchangeRateProcessor;
use Panda\Trade\Infrastructure\ApiState\Provider\ExchangeRateBaseQuoteProvider;
use Panda\Trade\Infrastructure\ApiState\Provider\ExchangeRateProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'ExchangeRate',
    operations: [
        new GetCollection(),
        new Get(),
        new Get(
            uriTemplate: '/exchange_rates/{baseAssetId}/{quoteAssetId}',
            uriVariables: [
                'baseAssetId' => new Link(toProperty: 'baseAsset', fromClass: AssetResource::class),
                'quoteAssetId' => new Link(toProperty: 'quoteAsset', fromClass: AssetResource::class),
            ],
            provider: ExchangeRateBaseQuoteProvider::class,
        ),
        new Post(validationContext: ['groups' => ['panda:create']]),
        new Patch(validationContext: ['groups' => ['panda:update']]),
        new Delete(),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP, self::UPDATE_GROUP]],
    provider: ExchangeRateProvider::class,
    processor: ExchangeRateProcessor::class,
)]
final class ExchangeRateResource
{
    public const READ_GROUP = 'exchange_rate:read';
    public const CREATE_GROUP = 'exchange_rate:create';
    public const UPDATE_GROUP = 'exchange_rate:update';

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['panda:create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?AssetResource $baseAsset = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['panda:create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?AssetResource $quoteAsset = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['panda:create', 'panda:update'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP, self::UPDATE_GROUP])]
        public ?float $rate = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $createdAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }

    public static function fromModel(ExchangeRateInterface $exchangeRate): ExchangeRateResource
    {
        return new self(
            $exchangeRate->getId(),
            AssetResource::fromModel($exchangeRate->getBaseAsset()),
            AssetResource::fromModel($exchangeRate->getQuoteAsset()),
            $exchangeRate->getRate(),
            $exchangeRate->getCreatedAt(),
            $exchangeRate->getUpdatedAt(),
        );
    }
}
