<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

enum TransactionTypeEnum: string
{
    case ASK = 'ask';
    case BID = 'bid';
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case FEE = 'fee';
}
