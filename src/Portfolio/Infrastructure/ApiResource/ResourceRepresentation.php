<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;

final class ResourceRepresentation
{
    public function __construct(
        #[ApiProperty(default: 'ACM')]
        #[Groups([PortfolioResource::READ_GROUP, PortfolioResource::CREATE_GROUP])]
        public ?string $ticker = null,

        #[ApiProperty(default: 'Acme Corp.')]
        #[Groups([PortfolioResource::READ_GROUP, PortfolioResource::CREATE_GROUP])]
        public ?string $name = null,
    ) {
    }

    public static function fromValueObject(ResourceInterface $resource): ResourceRepresentation
    {
        return new self(
            $resource->getTicker(),
            $resource->getName(),
        );
    }
}
