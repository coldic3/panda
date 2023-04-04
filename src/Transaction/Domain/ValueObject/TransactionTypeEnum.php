<?php

declare(strict_types=1);

namespace Panda\Transaction\Domain\ValueObject;

enum TransactionTypeEnum: string
{
    case ASK = 'ask';               // -FROM +TO    (+- fees/adjustments)
    case BID = 'bid';               // -FROM +TO    (+- fees/adjustments)
    case DEPOSIT = 'deposit';       // +TO          (+- fees/adjustments)
    case WITHDRAW = 'withdraw';     // -FROM        (+- fees/adjustments)
    case TRANSFER = 'transfer';     // +TO          (+- fees/adjustments)
    case FEE = 'fee';               //              (+- fees/adjustments)
}
