<?php

declare(strict_types=1);

namespace Panda\Tests\App\Trade\Domain\Factory;

use Panda\Account\Domain\Model\UserInterface;
use Panda\Trade\Domain\Factory\TransactionFactory;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Model\Transaction\Operation;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class TransactionFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_ask_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $thirdResource = $this->prophesize(AssetInterface::class);
        $fourthResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());
        $transaction = $factory->createAsk(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [
                new Operation($thirdResource->reveal(), 10),
                new Operation($fourthResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );

        Assert::uuid($transaction->getId());
        $this->assertSame(TransactionTypeEnum::ASK, $transaction->getType());
        $this->assertSame(100, $transaction->getFromOperation()->getQuantity());
        $this->assertSame(245, $transaction->getToOperation()->getQuantity());
        $this->assertSame(10, $transaction->getAdjustmentOperations()->get(0)->getQuantity());
        $this->assertSame(20, $transaction->getAdjustmentOperations()->get(1)->getQuantity());
    }

    /** @test */
    function it_creates_bid_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $thirdResource = $this->prophesize(AssetInterface::class);
        $fourthResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());
        $transaction = $factory->createBid(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [
                new Operation($thirdResource->reveal(), 10),
                new Operation($fourthResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );

        Assert::uuid($transaction->getId());
        $this->assertSame(TransactionTypeEnum::BID, $transaction->getType());
        $this->assertSame(100, $transaction->getFromOperation()->getQuantity());
        $this->assertSame(245, $transaction->getToOperation()->getQuantity());
        $this->assertSame(10, $transaction->getAdjustmentOperations()->get(0)->getQuantity());
        $this->assertSame(20, $transaction->getAdjustmentOperations()->get(1)->getQuantity());
    }

    /** @test */
    function it_creates_deposit_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $thirdResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());
        $transaction = $factory->createDeposit(
            new Operation($firstResource->reveal(), 245),
            [
                new Operation($secondResource->reveal(), 10),
                new Operation($thirdResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );

        Assert::uuid($transaction->getId());
        $this->assertSame(TransactionTypeEnum::DEPOSIT, $transaction->getType());
        $this->assertNull($transaction->getFromOperation());
        $this->assertSame(245, $transaction->getToOperation()->getQuantity());
        $this->assertSame(10, $transaction->getAdjustmentOperations()->get(0)->getQuantity());
        $this->assertSame(20, $transaction->getAdjustmentOperations()->get(1)->getQuantity());
    }

    /** @test */
    function it_creates_withdraw_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $thirdResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());
        $transaction = $factory->createWithdraw(
            new Operation($firstResource->reveal(), 245),
            [
                new Operation($secondResource->reveal(), 10),
                new Operation($thirdResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );

        Assert::uuid($transaction->getId());
        $this->assertSame(TransactionTypeEnum::WITHDRAW, $transaction->getType());
        $this->assertSame(245, $transaction->getFromOperation()->getQuantity());
        $this->assertNull($transaction->getToOperation());
        $this->assertSame(10, $transaction->getAdjustmentOperations()->get(0)->getQuantity());
        $this->assertSame(20, $transaction->getAdjustmentOperations()->get(1)->getQuantity());
    }

    /** @test */
    function it_creates_fee_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());
        $transaction = $factory->createFee(
            [
                new Operation($firstResource->reveal(), 10),
                new Operation($secondResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );

        Assert::uuid($transaction->getId());
        $this->assertSame(TransactionTypeEnum::FEE, $transaction->getType());
        $this->assertNull($transaction->getFromOperation());
        $this->assertNull($transaction->getToOperation());
        $this->assertSame(10, $transaction->getAdjustmentOperations()->get(0)->getQuantity());
        $this->assertSame(20, $transaction->getAdjustmentOperations()->get(1)->getQuantity());
    }

    /** @test */
    function it_creates_transaction_with_owner()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $thirdResource = $this->prophesize(AssetInterface::class);
        $fourthResource = $this->prophesize(AssetInterface::class);

        $factory = new TransactionFactory($security->reveal());

        $transaction = $factory->createAsk(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [
                new Operation($thirdResource->reveal(), 10),
                new Operation($fourthResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
            $owner->reveal(),
        );
        $this->assertSame($owner->reveal(), $transaction->getOwnedBy());

        $transaction = $factory->createBid(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [
                new Operation($thirdResource->reveal(), 10),
                new Operation($fourthResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
            $owner->reveal(),
        );
        $this->assertSame($owner->reveal(), $transaction->getOwnedBy());

        $transaction = $factory->createDeposit(
            new Operation($firstResource->reveal(), 245),
            [
                new Operation($secondResource->reveal(), 10),
                new Operation($thirdResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
            $owner->reveal(),
        );
        $this->assertSame($owner->reveal(), $transaction->getOwnedBy());

        $transaction = $factory->createWithdraw(
            new Operation($firstResource->reveal(), 245),
            [
                new Operation($secondResource->reveal(), 10),
                new Operation($thirdResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
            $owner->reveal(),
        );
        $this->assertSame($owner->reveal(), $transaction->getOwnedBy());

        $transaction = $factory->createFee(
            [
                new Operation($firstResource->reveal(), 10),
                new Operation($secondResource->reveal(), 20),
            ],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
            $owner->reveal(),
        );
        $this->assertSame($owner->reveal(), $transaction->getOwnedBy());
    }

    /** @test */
    function it_does_not_require_adjustment_operations_for_all_transactions_except_fee_transaction()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $firstResource = $this->prophesize(AssetInterface::class);
        $secondResource = $this->prophesize(AssetInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new TransactionFactory($security->reveal());

        $transaction = $factory->createAsk(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );
        Assert::uuid($transaction->getId());

        $transaction = $factory->createBid(
            new Operation($firstResource->reveal(), 100),
            new Operation($secondResource->reveal(), 245),
            [],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );
        Assert::uuid($transaction->getId());

        $transaction = $factory->createDeposit(
            new Operation($firstResource->reveal(), 245),
            [],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );
        Assert::uuid($transaction->getId());

        $transaction = $factory->createWithdraw(
            new Operation($firstResource->reveal(), 245),
            [],
            new \DateTimeImmutable('2023-04-04 23:02:37'),
        );
        Assert::uuid($transaction->getId());

        try {
            $exceptionThrown = false;
            $factory->createFee(
                [],
                new \DateTimeImmutable('2023-04-04 23:02:37'),
            );
        } catch (\InvalidArgumentException) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }
}
