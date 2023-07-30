<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Panda\Exchange\Domain\Model\ExchangeRateLiveInterface;
use Panda\Exchange\Infrastructure\ApiState\Processor\ExchangeRateLiveProcessor;
use Panda\Exchange\Infrastructure\ApiState\Provider\ExchangeRateLiveProvider;
use Panda\Exchange\Infrastructure\OpenApi\Filter\BaseQuoteResourcesFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'ExchangeRateLive',
    operations: [
        new GetCollection(filters: [BaseQuoteResourcesFilter::class]),
        new Get(),
        new Post(validationContext: ['groups' => ['panda:create']]),
        new Patch(validationContext: ['groups' => ['panda:update']]),
        new Delete(),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP, self::UPDATE_GROUP]],
    provider: ExchangeRateLiveProvider::class,
    processor: ExchangeRateLiveProcessor::class,
)]
final class ExchangeRateLiveResource
{
    public const READ_GROUP = 'exchange_rate_live:read';
    public const CREATE_GROUP = 'exchange_rate_live:create';
    public const UPDATE_GROUP = 'exchange_rate_live:update';

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['panda:create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?string $baseTicker = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['panda:create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?string $quoteTicker = null,

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

    public static function fromModel(ExchangeRateLiveInterface $exchangeRate): ExchangeRateLiveResource
    {
        return new self(
            $exchangeRate->getId(),
            $exchangeRate->getBaseTicker(),
            $exchangeRate->getQuoteTicker(),
            $exchangeRate->getRate(),
            $exchangeRate->getCreatedAt(),
            $exchangeRate->getUpdatedAt(),
        );
    }
}
