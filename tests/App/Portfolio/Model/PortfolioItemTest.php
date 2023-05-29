<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Model;

use Panda\Portfolio\Domain\Exception\NegativeQuantityException;
use Panda\Portfolio\Domain\Exception\NegativeTotalQuantityException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItem;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class PortfolioItemTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_has_zero_quantity_at_first()
    {
        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $this->assertSame(0, $portfolioItem->getLongQuantity());
        $this->assertSame(0, $portfolioItem->getShortQuantity());
    }

    /** @test */
    function it_adds_and_removes_quantity()
    {
        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addLongQuantity(10);
        $portfolioItem->addLongQuantity(0);
        $portfolioItem->addLongQuantity(5);
        $portfolioItem->removeLongQuantity(2);
        $portfolioItem->removeLongQuantity(0);
        $portfolioItem->addLongQuantity(1);

        $portfolioItem->addShortQuantity(10);
        $portfolioItem->addShortQuantity(0);
        $portfolioItem->addShortQuantity(5);
        $portfolioItem->removeShortQuantity(2);
        $portfolioItem->removeShortQuantity(0);
        $portfolioItem->addShortQuantity(1);

        $this->assertSame(14, $portfolioItem->getLongQuantity());
        $this->assertSame(14, $portfolioItem->getShortQuantity());
    }

    /** @test */
    function it_can_have_quantity_reduced_to_zero()
    {
        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addLongQuantity(10);
        $portfolioItem->removeLongQuantity(10);

        $portfolioItem->addShortQuantity(10);
        $portfolioItem->removeShortQuantity(10);

        $this->assertSame(0, $portfolioItem->getLongQuantity());
        $this->assertSame(0, $portfolioItem->getShortQuantity());
    }

    /** @test */
    function it_throws_exception_if_negative_long_quantity_added()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addLongQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_negative_short_quantity_added()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addShortQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_negative_long_quantity_removed()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->removeLongQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_negative_short_quantity_removed()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->removeShortQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_total_long_quantity_is_negative()
    {
        $this->expectException(NegativeTotalQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addLongQuantity(10);
        $portfolioItem->removeLongQuantity(11);
    }

    /** @test */
    function it_throws_exception_if_total_short_quantity_is_negative()
    {
        $this->expectException(NegativeTotalQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addShortQuantity(10);
        $portfolioItem->removeShortQuantity(11);
    }
}
