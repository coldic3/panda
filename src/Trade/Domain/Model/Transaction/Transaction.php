<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Transaction;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use Symfony\Component\Uid\Uuid;

/** @readonly */
class Transaction implements TransactionInterface
{
    private Uuid $id;
    private ?OwnerInterface $owner = null;

    /** @var Collection<array-key, OperationInterface> */
    private Collection $adjustmentOperations;

    /**
     * @param OperationInterface[] $adjustmentOperations
     */
    public function __construct(
        private readonly TransactionTypeEnum $type,
        private readonly ?OperationInterface $fromOperation,
        private readonly ?OperationInterface $toOperation,
        array $adjustmentOperations,
        private readonly \DateTimeInterface $concludedAt,
    ) {
        $this->id = Uuid::v4();

        $this->adjustmentOperations = new ArrayCollection($adjustmentOperations);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function getFromOperation(): ?OperationInterface
    {
        return $this->fromOperation;
    }

    public function getToOperation(): ?OperationInterface
    {
        return $this->toOperation;
    }

    public function getAdjustmentOperations(): Collection
    {
        return $this->adjustmentOperations;
    }

    public function getConcludedAt(): \DateTimeInterface
    {
        return $this->concludedAt;
    }

    public function getOwnedBy(): ?OwnerInterface
    {
        return $this->owner;
    }

    public function setOwnedBy(OwnerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
