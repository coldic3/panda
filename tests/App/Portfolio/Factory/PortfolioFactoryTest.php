<?php

declare(strict_types=1);

namespace Panda\Tests\App\Portfolio\Factory;

use Panda\Account\Domain\Model\UserInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Portfolio\Domain\Factory\PortfolioFactory;
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
        $portfolio = $factory->create('my first portfolio', true);

        Assert::uuid($portfolio->getId());
        $this->assertSame('my first portfolio', $portfolio->getName());
        $this->assertSame(true, $portfolio->isDefault());
    }

    /** @test */
    function it_creates_portfolio_with_user()
    {
        $authorizedUserProvider = $this->prophesize(AuthorizedUserProviderInterface::class);
        $owner = $this->prophesize(UserInterface::class);
        $authorizedUserProvider->provide()->shouldNotBeCalled();

        $factory = new PortfolioFactory($authorizedUserProvider->reveal());
        $portfolio = $factory->create('my second portfolio', false, $owner->reveal());

        Assert::uuid($portfolio->getId());
        $this->assertSame('my second portfolio', $portfolio->getName());
        $this->assertSame(false, $portfolio->isDefault());
        $this->assertSame($owner->reveal(), $portfolio->getOwnedBy());
    }
}
