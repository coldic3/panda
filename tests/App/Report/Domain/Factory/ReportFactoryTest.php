<?php

declare(strict_types=1);

namespace Panda\Tests\App\Report\Domain\Factory;

use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Report\Domain\Factory\ReportFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Webmozart\Assert\Assert;

final class ReportFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_report()
    {
        $portfolio = $this->prophesize(PortfolioInterface::class);

        $factory = new ReportFactory();
        $report = $factory->create('Test Report 1', 'test', ['accuracy' => 0.5], $portfolio->reveal());

        Assert::uuid($report->getId());
        $this->assertSame('Test Report 1', $report->getName());
        $this->assertSame('test', $report->getEntry()->getType());
        $this->assertSame(['accuracy' => 0.5], $report->getEntry()->getConfiguration());
        $this->assertNull($report->getFile()->getStorage());
        $this->assertNull($report->getFile()->getFilename());
        $this->assertSame($portfolio->reveal(), $report->getPortfolio());
    }
}
