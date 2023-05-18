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
     * @Given zdeponowałem :quantity :asset w dniu :datetime
     */
    function i_deposit_at(int $quantity, AssetInterface $asset, \DateTimeImmutable $datetime)
    {
        $operation = $this->operationFactory->create($asset, $quantity);
        $transaction = $this->transactionFactory->createDeposit($operation, [], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given wypłaciłem z platformy inwestycyjnej :quantity :asset w dniu :datetime płacąc przy tym :adjustmentQuantity :adjustmentAsset prowizji
     */
    function i_withdraw_at(
        int $quantity,
        AssetInterface $asset,
        \DateTimeImmutable $datetime,
        int $adjustmentQuantity,
        AssetInterface $adjustmentAsset
    ) {
        $operation = $this->operationFactory->create($asset, $quantity);
        $adjustmentOperation = $this->operationFactory->create($adjustmentAsset, $adjustmentQuantity);
        $transaction = $this->transactionFactory->createWithdraw($operation, [$adjustmentOperation], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given kupiłem :toQuantity akcji spółki :toAsset w dniu :datetime za :fromQuantity :fromAsset
     * @Given kupiłem :toQuantity akcję spółki :toAsset w dniu :datetime za :fromQuantity akcję spółki :fromAsset
     */
    function i_ask_at(
        int $toQuantity,
        AssetInterface $toAsset,
        \DateTimeImmutable $datetime,
        int $fromQuantity,
        AssetInterface $fromAsset,
    ) {
        $fromOperation = $this->operationFactory->create($fromAsset, $fromQuantity);
        $toOperation = $this->operationFactory->create($toAsset, $toQuantity);
        $transaction = $this->transactionFactory->createAsk($fromOperation, $toOperation, [], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }

    /**
     * @Given sprzedałem :fromQuantity akcji spółki :fromAsset w dniu :datetime za :toQuantity :toAsset płacąc przy tym :adjustmentQuantity :adjustmentAsset prowizji
     */
    function i_bid_at(
        int $fromQuantity,
        AssetInterface $fromAsset,
        \DateTimeImmutable $datetime,
        int $toQuantity,
        AssetInterface $toAsset,
        int $adjustmentQuantity,
        AssetInterface $adjustmentAsset,
    ) {
        $fromOperation = $this->operationFactory->create($fromAsset, $fromQuantity);
        $toOperation = $this->operationFactory->create($toAsset, $toQuantity);
        $adjustmentOperation = $this->operationFactory->create($adjustmentAsset, $adjustmentQuantity);
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
     * @Given platforma inwestycyjna pobrała opłatę w wysokości :quantity :asset w dniu :datetime
     */
    function i_pay_fee_at(int $quantity, AssetInterface $asset, \DateTimeImmutable $datetime)
    {
        $operation = $this->operationFactory->create($asset, $quantity);
        $transaction = $this->transactionFactory->createFee([$operation], $datetime);

        $this->transactionRepository->save($transaction);
        $this->entityManager->flush();
    }
}
