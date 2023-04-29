<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Panda\Trade\Domain\Model\OperationInterface;
use Panda\Trade\Domain\Model\TransactionInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use Panda\Trade\Infrastructure\ApiState\Processor\TransactionCreateProcessor;
use Panda\Trade\Infrastructure\ApiState\Provider\TransactionProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Transaction',
    operations: [
        new Post(validationContext: ['groups' => ['create']], processor: TransactionCreateProcessor::class),
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::CREATE_GROUP]],
    provider: TransactionProvider::class,
)]
final class TransactionResource
{
    public const READ_GROUP = 'transaction:read';
    public const CREATE_GROUP = 'transaction:create';

    /**
     * @param OperationResource[]|null $adjustmentOperations
     */
    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['create'])]
        #[Assert\Choice(
            choices: [
                TransactionTypeEnum::ASK,
                TransactionTypeEnum::BID,
                TransactionTypeEnum::DEPOSIT,
                TransactionTypeEnum::WITHDRAW,
                TransactionTypeEnum::FEE,
            ],
            groups: ['create'],
        )]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?TransactionTypeEnum $type = null,

        #[ApiProperty]
        #[Assert\NotBlank(allowNull: true, groups: ['create'])]
        #[Assert\Valid(groups: ['create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?OperationResource $fromOperation = null,

        #[ApiProperty]
        #[Assert\NotBlank(allowNull: true, groups: ['create'])]
        #[Assert\Valid(groups: ['create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?OperationResource $toOperation = null,

        #[ApiProperty]
        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Valid(groups: ['create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?array $adjustmentOperations = null,

        #[ApiProperty]
        #[Assert\NotBlank(groups: ['create'])]
        #[Groups([self::READ_GROUP, self::CREATE_GROUP])]
        public ?\DateTimeInterface $concludedAt = null,
    ) {
    }

    public function addAdjustmentOperation(OperationResource $operation): void
    {
        $this->adjustmentOperations[] = $operation;
    }

    public static function fromModel(TransactionInterface $transaction): TransactionResource
    {
        return new self(
            $transaction->getId(),
            $transaction->getType(),
            null === $transaction->getFromOperation() ? null : OperationResource::fromModel($transaction->getFromOperation()),
            null === $transaction->getToOperation() ? null : OperationResource::fromModel($transaction->getToOperation()),
            $transaction->getAdjustmentOperations()->map(
                fn (OperationInterface $operation) => OperationResource::fromModel($operation)
            )->toArray(),
            $transaction->getConcludedAt(),
        );
    }
}
