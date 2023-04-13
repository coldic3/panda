<?php

declare(strict_types=1);

use Panda\Transaction\Application\Validator\Command\TransactionOperationAdjustmentsMatchValidation;
use Panda\Transaction\Application\Validator\Command\TransactionOperationsDifferValidation;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TransactionOperationsDifferValidation::class)
        ->tag('validator.constraint_validator', ['alias' => 'panda_transaction_operations_differ']);

    $services->set(TransactionOperationAdjustmentsMatchValidation::class)
        ->tag('validator.constraint_validator', ['alias' => 'panda_transaction_operation_adjustments_match']);
};
