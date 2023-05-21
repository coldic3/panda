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

        $this->assertSame(0, $portfolioItem->getQuantity());
    }

    /** @test */
    function it_adds_and_removes_quantity()
    {
        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addQuantity(10);
        $portfolioItem->addQuantity(0);
        $portfolioItem->addQuantity(5);
        $portfolioItem->removeQuantity(2);
        $portfolioItem->removeQuantity(0);
        $portfolioItem->addQuantity(1);

        $this->assertSame(14, $portfolioItem->getQuantity());
    }

    /** @test */
    function it_can_have_quantity_reduced_to_zero()
    {
        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addQuantity(10);
        $portfolioItem->removeQuantity(10);

        $this->assertSame(0, $portfolioItem->getQuantity());
    }

    /** @test */
    function it_throws_exception_if_negative_quantity_added()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_negative_quantity_removed()
    {
        $this->expectException(NegativeQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->removeQuantity(-1);
    }

    /** @test */
    function it_throws_exception_if_total_quantity_is_negative()
    {
        $this->expectException(NegativeTotalQuantityException::class);

        $portfolioItem = new PortfolioItem(
            $this->prophesize(ResourceInterface::class)->reveal(),
            $this->prophesize(PortfolioInterface::class)->reveal(),
        );

        $portfolioItem->addQuantity(10);
        $portfolioItem->removeQuantity(11);
    }
}
