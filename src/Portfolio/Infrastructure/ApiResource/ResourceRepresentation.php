<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Symfony\Component\Serializer\Annotation\Groups;

final class ResourceRepresentation
{
    public function __construct(
        #[ApiProperty(writable: false, default: 'ACM')]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?string $ticker = null,

        #[ApiProperty(writable: false, default: 'Acme Corp.')]
        #[Groups([PortfolioResource::READ_GROUP])]
        public ?string $name = null,
    ) {
    }

    public static function fromModel(PortfolioItemInterface $portfolioItem): ResourceRepresentation
    {
        $resource = $portfolioItem->getResource();

        return new self(
            $resource->getTicker(),
            $resource->getName(),
        );
    }
}
