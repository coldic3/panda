<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Domain\Model;

use Panda\Portfolio\Domain\Model\Portfolio\Portfolio;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class PortfolioTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_adds_items()
    {
        $resource = $this->prophesize(ResourceInterface::class)->reveal();

        $portfolio = new Portfolio('name', $resource);
        $firstItem = $this->prophesize(PortfolioItemInterface::class)->reveal();
        $secondItem = $this->prophesize(PortfolioItemInterface::class)->reveal();

        $portfolio->addItem($firstItem);
        $portfolio->addItem($secondItem);

        $this->assertCount(2, $portfolio->getItems());
    }

    /** @test */
    function it_adds_items_with_no_duplicates()
    {
        $resource = $this->prophesize(ResourceInterface::class)->reveal();

        $portfolio = new Portfolio('name', $resource);
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
        $resource = $this->prophesize(ResourceInterface::class)->reveal();

        $portfolio = new Portfolio('name', $resource);
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
