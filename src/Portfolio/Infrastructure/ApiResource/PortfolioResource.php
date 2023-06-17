<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioChangeDefaultProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioCreateProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Processor\PortfolioUpdateProcessor;
use Panda\Portfolio\Infrastructure\ApiState\Provider\PortfolioProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Portfolio',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            validationContext: ['groups' => ['create']],
            processor: PortfolioCreateProcessor::class
        ),
        new Patch(
            validationContext: ['groups' => ['create']],
            processor: PortfolioUpdateProcessor::class,
        ),
        new Patch(
            uriTemplate: '/portfolios/{id}/default',
            denormalizationContext: ['groups' => []],
            validationContext: ['groups' => ['create']],
            processor: PortfolioChangeDefaultProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP, self::UPDATE_GROUP]],
    provider: PortfolioProvider::class,
)]
final class PortfolioResource
{
    public const READ_GROUP = 'portfolio:read';
    public const CREATE_GROUP = 'portfolio:create';
    public const UPDATE_GROUP = 'portfolio:update';

    /**
     * @param PortfolioItemResource[]|null $items
     */
    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP, self::UPDATE_GROUP])]
        public ?string $name = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?bool $default = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?array $items = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $createdAt = null,

        #[ApiProperty(writable: false)]
        #[Groups([self::READ_GROUP])]
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }

    public function addItem(PortfolioItemResource $item): void
    {
        $this->items[] = $item;
    }

    public static function fromModel(PortfolioInterface $portfolio): PortfolioResource
    {
        return new self(
            $portfolio->getId(),
            $portfolio->getName(),
            $portfolio->isDefault(),
            $portfolio->getItems()->map(
                fn (PortfolioItemInterface $item) => PortfolioItemResource::fromModel($item)
            )->toArray(),
            $portfolio->getCreatedAt(),
            $portfolio->getUpdatedAt(),
        );
    }
}
