<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model;

use Doctrine\Common\Collections\Collection;
use Panda\Contract\AggregateRoot\Owner\OwnershipInterface;
use Panda\Shared\Domain\Model\IdentifiableInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;

interface TransactionInterface extends IdentifiableInterface, OwnershipInterface
{
    public function getType(): TransactionTypeEnum;

    public function getFromOperation(): ?OperationInterface;

    public function getToOperation(): ?OperationInterface;

    /** @return Collection<array-key, OperationInterface> */
    public function getAdjustmentOperations(): Collection;

    public function getConcludedAt(): \DateTimeInterface;
}
