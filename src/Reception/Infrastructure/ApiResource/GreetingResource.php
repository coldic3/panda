<?php

declare(strict_types=1);

namespace Panda\Reception\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Infrastructure\ApiState\Processor\GreetingCrudProcesor;
use Panda\Reception\Infrastructure\ApiState\Provider\GreetingCrudProvider;
use Panda\Reception\Infrastructure\OpenApi\Filter\NameFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Greeting',
    operations: [
        new GetCollection(
            filters: [NameFilter::class],
        ),
        new Get(),
        new Post(validationContext: ['groups' => ['create']]),
        new Put(extraProperties: ['standard_put' => true]),
        new Patch(),
        new Delete(),
    ],
    denormalizationContext: ['groups' => ['create', 'update']],
    provider: GreetingCrudProvider::class,
    processor: GreetingCrudProcesor::class
)]
final class GreetingResource
{
    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 1, max: 255, groups: ['create', 'Default'])]
        #[Groups(['create', 'update'])]
        public ?string $name = null,
    ) {
    }

    public static function fromModel(Greeting $greeting): GreetingResource
    {
        return new self($greeting->id, $greeting->name);
    }
}
