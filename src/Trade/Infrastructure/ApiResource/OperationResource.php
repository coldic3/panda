<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

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
        #[Groups([TransactionResource::READ_GROUP, TransactionResource::CREATE_GROUP])]
        public ?AssetResource $asset = null,

        #[ApiProperty]
        #[Groups([TransactionResource::READ_GROUP, TransactionResource::CREATE_GROUP])]
        public ?int $quantity = null,
    ) {
    }

    public static function fromModel(OperationInterface $operation): OperationResource
    {
        Assert::isInstanceOf(
            $asset = $operation->getAsset(),
            AssetInterface::class,
        );

        return new self(
            $operation->getId(),
            AssetResource::fromModel($asset),
            $operation->getQuantity(),
        );
    }
}
