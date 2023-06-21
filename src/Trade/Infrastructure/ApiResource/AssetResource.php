<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Infrastructure\ApiState\Processor\AssetChangeTickerProcessor;
use Panda\Trade\Infrastructure\ApiState\Processor\AssetProcessor;
use Panda\Trade\Infrastructure\ApiState\Provider\AssetProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Asset',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(validationContext: ['groups' => ['create']]),
        new Patch(validationContext: ['groups' => ['update']]),
        new Patch(
            uriTemplate: '/assets/{id}/ticker',
            denormalizationContext: ['groups' => [self::UPDATE_TICKER_GROUP]],
            validationContext: ['groups' => ['update']],
            processor: AssetChangeTickerProcessor::class,
        ),
        new Delete(),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP, self::UPDATE_GROUP]],
    provider: AssetProvider::class,
    processor: AssetProcessor::class,
)]
final class AssetResource
{
    public const READ_GROUP = 'asset:read';
    public const CREATE_GROUP = 'asset:create';
    public const UPDATE_GROUP = 'asset:update';
    public const UPDATE_TICKER_GROUP = 'asset:update:ticker';

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty(default: 'ACM')]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP, self::UPDATE_TICKER_GROUP])]
        public ?string $ticker = null,

        #[ApiProperty(default: 'Acme Corp.')]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP, self::UPDATE_GROUP])]
        public ?string $name = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $createdAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }

    public static function fromModel(AssetInterface $asset): AssetResource
    {
        return new self(
            $asset->getId(),
            $asset->getTicker(),
            $asset->getName(),
            $asset->getCreatedAt(),
            $asset->getUpdatedAt(),
        );
    }
}
