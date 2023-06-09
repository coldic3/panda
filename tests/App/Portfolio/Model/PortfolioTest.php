<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Model;

use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class PortfolioTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_adds_items()
    {
        $portfolio = new Portfolio('name');
        $firstItem = $this->prophesize(PortfolioItemInterface::class)->reveal();
        $secondItem = $this->prophesize(PortfolioItemInterface::class)->reveal();

        $portfolio->addItem($firstItem);
        $portfolio->addItem($secondItem);

        $this->assertCount(2, $portfolio->getItems());
    }

    /** @test */
    function it_adds_items_with_no_duplicates()
    {
        $portfolio = new Portfolio('name');
        $firstItem = $this->prophesize(PortfolioItemInterface::class)->reveal();
        $secondItem = $this->prophesize(PortfolioItemInterface::class)->reveal();

        $portfolio->addItem($firstItem);
        $portfolio->addItem($secondItem);
        $portfolio->addItem($firstItem);

        $this->assertCount(2, $portfolio->getItems());
    }

    /** @test */
    function it_removes_items()
    {
        $portfolio = new Portfolio('name');
        $firstItem = $this->prophesize(PortfolioItemInterface::class)->reveal();
        $secondItem = $this->prophesize(PortfolioItemInterface::class)->reveal();

        $portfolio->addItem($firstItem);
        $portfolio->addItem($secondItem);
        $portfolio->removeItem($firstItem);

        $this->assertCount(1, $portfolio->getItems());
        $this->assertContains($secondItem, $portfolio->getItems());
        $this->assertNotContains($firstItem, $portfolio->getItems());
    }
}
