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
        new Delete(),
    ],
    normalizationContext: ['groups' => self::READABLE_GROUPS],
    denormalizationContext: ['groups' => self::WRITABLE_GROUPS],
    provider: AssetProvider::class,
    processor: AssetProcessor::class,
)]
final class AssetResource
{
    private const READABLE_GROUPS = ['read'];
    private const WRITABLE_GROUPS = ['create', 'update'];

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty(default: 'ACM')]
        #[Groups([...self::READABLE_GROUPS, ...self::WRITABLE_GROUPS])]
        public ?string $ticker = null,

        #[ApiProperty(default: 'Acme Corp.')]
        #[Groups([...self::READABLE_GROUPS, ...self::WRITABLE_GROUPS])]
        public ?string $name = null,

        #[ApiProperty(writable: false)]
        #[Groups(self::READABLE_GROUPS)]
        public ?\DateTimeInterface $createdAt = null,

        #[ApiProperty(writable: false)]
        #[Groups(self::READABLE_GROUPS)]
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
