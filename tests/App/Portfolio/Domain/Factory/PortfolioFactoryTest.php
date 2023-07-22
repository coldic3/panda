<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Domain\Factory;

use Panda\Account\Domain\Model\UserInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactory;
use Panda\Portfolio\Domain\ValueObject\Resource;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Webmozart\Assert\Assert;

final class PortfolioFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_portfolio()
    {
        $authorizedUserProvider = $this->prophesize(AuthorizedUserProviderInterface::class);
        $owner = $this->prophesize(UserInterface::class);
        $authorizedUserProvider->provide()->willReturn($owner);

        $factory = new PortfolioFactory($authorizedUserProvider->reveal());
        $portfolio = $factory->create('my first portfolio', 'ACME', 'ACME Inc.', true);

        Assert::uuid($portfolio->getId());
        $this->assertSame('my first portfolio', $portfolio->getName());
        $this->assertSame(true, $portfolio->isDefault());
        $this->assertEquals(new Resource('ACME', 'ACME Inc.'), $portfolio->getMainResource());
    }

    /** @test */
    function it_creates_portfolio_with_user()
    {
        $authorizedUserProvider = $this->prophesize(AuthorizedUserProviderInterface::class);
        $owner = $this->prophesize(UserInterface::class);
        $authorizedUserProvider->provide()->shouldNotBeCalled();

        $factory = new PortfolioFactory($authorizedUserProvider->reveal());
        $portfolio = $factory->create('my second portfolio', 'ACME', 'ACME Inc.', false, $owner->reveal());

        Assert::uuid($portfolio->getId());
        $this->assertSame('my second portfolio', $portfolio->getName());
        $this->assertSame(false, $portfolio->isDefault());
        $this->assertEquals(new Resource('ACME', 'ACME Inc.'), $portfolio->getMainResource());
        $this->assertSame($owner->reveal(), $portfolio->getOwnedBy());
    }
}
