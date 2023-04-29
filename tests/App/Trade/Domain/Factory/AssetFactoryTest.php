<?php

declare(strict_types=1);

namespace Panda\Tests\App\Trade\Domain\Factory;

use Panda\Account\Domain\Model\UserInterface;
use Panda\Trade\Domain\Factory\AssetFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class AssetFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_asset()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new AssetFactory($security->reveal());
        $asset = $factory->create('AAPL', 'Apple Inc.');

        Assert::uuid($asset->getId());
        $this->assertSame('AAPL', $asset->getTicker());
        $this->assertSame('Apple Inc.', $asset->getName());
    }

    /** @test */
    function it_creates_asset_without_name()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);
        $security->getUser()->willReturn($owner);

        $factory = new AssetFactory($security->reveal());
        $asset = $factory->create('AAPL');

        Assert::uuid($asset->getId());
        $this->assertSame('AAPL', $asset->getTicker());
        $this->assertSame('AAPL', $asset->getName());
    }

    /** @test */
    function it_creates_asset_with_owner()
    {
        $security = $this->prophesize(Security::class);
        $owner = $this->prophesize(UserInterface::class);

        $factory = new AssetFactory($security->reveal());
        $asset = $factory->create('AAPL', 'Apple Inc.', $owner->reveal());

        Assert::uuid($asset->getId());
        $this->assertSame('AAPL', $asset->getTicker());
        $this->assertSame('Apple Inc.', $asset->getName());
        $this->assertSame($owner->reveal(), $asset->getOwnedBy());
    }
}
