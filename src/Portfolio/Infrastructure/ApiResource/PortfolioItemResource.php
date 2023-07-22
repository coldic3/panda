<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'PortfolioItem',
    operations: [],
    normalizationContext: ['groups' => [PortfolioResource::READ_GROUP]],
)]
final class PortfolioItemResource
{
    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty(writable: false, genId: false)]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?ResourceRepresentation $resource = null,

        #[ApiProperty(writable: false)]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?QuantityRepresentation $quantity = null,
    ) {
    }

    public static function fromModel(PortfolioItemInterface $portfolioItem): PortfolioItemResource
    {
        return new self(
            $portfolioItem->getId(),
            ResourceRepresentation::fromValueObject($portfolioItem->getResource()),
            QuantityRepresentation::fromModel($portfolioItem),
        );
    }
}
