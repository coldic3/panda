<?php

declare(strict_types=1);

namespace Panda\Transaction\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandInterface;
use Panda\Transaction\Domain\Model\OperationInterface;
use Panda\Transaction\Domain\ValueObject\TransactionTypeEnum;

final readonly class CreateTransactionCommand implements CommandInterface
{
    /**
     * @param OperationInterface[] $adjustmentOperations
     */
    public function __construct(
        public TransactionTypeEnum $type,
        public ?OperationInterface $fromOperation,
        public ?OperationInterface $toOperation,
        public array $adjustmentOperations,
        public \DateTimeInterface $concludedAt,
    ) {
    }
}
