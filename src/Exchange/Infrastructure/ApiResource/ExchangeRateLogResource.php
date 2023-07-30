<?php

declare(strict_types=1);

namespace Panda\Exchange\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Panda\Exchange\Domain\Model\ExchangeRateLogInterface;
use Panda\Exchange\Infrastructure\ApiState\Processor\ExchangeRateLogProcessor;
use Panda\Exchange\Infrastructure\ApiState\Provider\ExchangeRateLogProvider;
use Panda\Exchange\Infrastructure\OpenApi\Filter\BaseQuoteResourcesFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'ExchangeRateLog',
    operations: [
        new GetCollection(filters: [BaseQuoteResourcesFilter::class]),
        new Get(),
        new Post(validationContext: ['groups' => ['panda:create']]),
        new Delete(),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP]],
    provider: ExchangeRateLogProvider::class,
    processor: ExchangeRateLogProcessor::class,
)]
final class ExchangeRateLogResource
{
    public const READ_GROUP = 'exchange_rate_log:read';
    public const CREATE_GROUP = 'exchange_rate_log:create';

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
        #[Assert\NotBlank(groups: ['panda:create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?float $rate = null,

        #[ApiProperty]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?\DateTimeInterface $startedAt = null,

        #[ApiProperty]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?\DateTimeInterface $endedAt = null,
    ) {
    }

    public static function fromModel(ExchangeRateLogInterface $exchangeRateLog): ExchangeRateLogResource
    {
        return new self(
            $exchangeRateLog->getId(),
            $exchangeRateLog->getBaseTicker(),
            $exchangeRateLog->getQuoteTicker(),
            $exchangeRateLog->getRate(),
            $exchangeRateLog->getStartedAt(),
            $exchangeRateLog->getEndedAt(),
        );
    }
}
