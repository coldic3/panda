<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Symfony\Component\Serializer\Annotation\Groups;

final class QuantityRepresentation
{
    public function __construct(
        #[ApiProperty(writable: false)]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?int $long = null,

        #[ApiProperty(writable: false)]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?int $short = null,
    ) {
    }

    public static function fromModel(PortfolioItemInterface $portfolioItem): QuantityRepresentation
    {
        return new self(
            $portfolioItem->getLongQuantity(),
            $portfolioItem->getShortQuantity(),
        );
    }
}
