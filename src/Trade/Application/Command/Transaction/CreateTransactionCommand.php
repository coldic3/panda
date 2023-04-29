<?php

declare(strict_types=1);

namespace Panda\Trade\Application\Command\Transaction;

use Panda\Shared\Application\Command\CommandInterface;
use Panda\Trade\Domain\Model\OperationInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;

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
