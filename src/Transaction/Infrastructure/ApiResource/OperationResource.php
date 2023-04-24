<?php

declare(strict_types=1);

namespace Panda\Transaction\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Infrastructure\ApiResource\AssetResource;
use Panda\Transaction\Domain\Model\OperationInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Operation',
    operations: [],
    normalizationContext: ['groups' => [TransactionResource::READ_GROUP]],
    denormalizationContext: ['groups' => [TransactionResource::CREATE_GROUP]],
)]
final class OperationResource
{
    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Assert\NotBlank(allowNull: true, groups: ['create'])]
        #[Groups([TransactionResource::READ_GROUP, TransactionResource::CREATE_GROUP])]
        public ?AssetResource $resource = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['create'])]
        #[Assert\Range(min: 1, groups: ['create'])]
        #[Groups([TransactionResource::READ_GROUP, TransactionResource::CREATE_GROUP])]
        public ?int $quantity = null,
    ) {
    }

    public static function fromModel(OperationInterface $operation): OperationResource
    {
        \Webmozart\Assert\Assert::isInstanceOf(
            $asset = $operation->getResource(),
            AssetInterface::class,
        );

        return new self(
            $operation->getId(),
            AssetResource::fromModel($asset),
            $operation->getQuantity(),
        );
    }
}
