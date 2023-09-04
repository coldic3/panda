<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Trade\Domain\Factory\OperationFactoryInterface;
use Panda\Trade\Domain\Factory\TransactionFactoryInterface;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\TransactionRepositoryInterface;

class TransactionContext implements Context
{
    public function __construct(
        private readonly TransactionFactoryInterface $transactionFactory,
        private readonly OperationFactoryInterface $operationFactory,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Given zdeponowałem :preciseQuantity :asset w dniu :datetime
     */
    function i_deposit_at(int $preciseQuantity, AssetInterface $asset, \DateTimeImmutable $datetime)
    {
        $operation = $this->operationFactory->create($asset, $preciseQuantity);
        $transaction = $this->transactionFactory->createDeposit($operation, [], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given wypłaciłem z platformy inwestycyjnej :preciseQuantity :asset w dniu :datetime płacąc przy tym :adjustmentPreciseQuantity :adjustmentAsset prowizji
     */
    function i_withdraw_at(
        int $preciseQuantity,
        AssetInterface $asset,
        \DateTimeImmutable $datetime,
        int $adjustmentPreciseQuantity,
        AssetInterface $adjustmentAsset
    ) {
        $operation = $this->operationFactory->create($asset, $preciseQuantity);
        $adjustmentOperation = $this->operationFactory->create($adjustmentAsset, $adjustmentPreciseQuantity);
        $transaction = $this->transactionFactory->createWithdraw($operation, [$adjustmentOperation], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given kupiłem :toQuantity akcji spółki :toAsset w dniu :datetime za :fromPreciseQuantity :fromAsset
     */
    function i_ask_at_currency(
        int $toQuantity,
        AssetInterface $toAsset,
        \DateTimeImmutable $datetime,
        int $fromPreciseQuantity,
        AssetInterface $fromAsset,
    ) {
        $fromOperation = $this->operationFactory->create($fromAsset, $fromPreciseQuantity);
        $toOperation = $this->operationFactory->create($toAsset, $toQuantity);
        $transaction = $this->transactionFactory->createAsk($fromOperation, $toOperation, [], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given kupiłem :toQuantity akcję spółki :toAsset w dniu :datetime za :fromQuantity akcję spółki :fromAsset
     */
    function i_ask_at_asset(
        int $toQuantity,
        AssetInterface $toAsset,
        \DateTimeImmutable $datetime,
        int $fromQuantity,
        AssetInterface $fromAsset,
    ) {
        $this->i_ask_at_currency($toQuantity, $toAsset, $datetime, $fromQuantity, $fromAsset);
    }

    /**
     * @Given sprzedałem :fromQuantity akcji spółki :fromAsset w dniu :datetime za :toPreciseQuantity :toAsset płacąc przy tym :adjustmentPreciseQuantity :adjustmentAsset prowizji
     */
    function i_bid_at(
        int $fromQuantity,
        AssetInterface $fromAsset,
        \DateTimeImmutable $datetime,
        int $toPreciseQuantity,
        AssetInterface $toAsset,
        int $adjustmentPreciseQuantity,
        AssetInterface $adjustmentAsset,
    ) {
        $fromOperation = $this->operationFactory->create($fromAsset, $fromQuantity);
        $toOperation = $this->operationFactory->create($toAsset, $toPreciseQuantity);
        $adjustmentOperation = $this->operationFactory->create($adjustmentAsset, $adjustmentPreciseQuantity);
        $transaction = $this->transactionFactory->createBid(
            $fromOperation,
            $toOperation,
            [$adjustmentOperation],
            $datetime,
        );

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given platforma inwestycyjna pobrała opłatę w wysokości :preciseQuantity :asset w dniu :datetime
     */
    function i_pay_fee_at(int $preciseQuantity, AssetInterface $asset, \DateTimeImmutable $datetime)
    {
        $operation = $this->operationFactory->create($asset, $preciseQuantity);
        $transaction = $this->transactionFactory->createFee([$operation], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }
}
