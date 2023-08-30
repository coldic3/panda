<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Domain\Factory;

use Panda\Portfolio\Domain\Factory\PortfolioItemFactory;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Webmozart\Assert\Assert;

final class PortfolioItemFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_portfolio()
    {
        $portfolio = $this->prophesize(PortfolioInterface::class);

        $factory = new PortfolioItemFactory();
        $portfolioItem = $factory->create('ACME', 'ACME Inc.', $portfolio->reveal());

        Assert::uuid($portfolioItem->getId());
        $this->assertSame('ACME', $portfolioItem->getResource()->getTicker());
        $this->assertSame('ACME Inc.', $portfolioItem->getResource()->getName());
        $this->assertSame($portfolio->reveal(), $portfolioItem->getPortfolio());
    }
}
