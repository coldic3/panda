<?php

declare(strict_types=1);

namespace Panda\Tests\App\Asset\Domain\Factory;

use Panda\Asset\Domain\Factory\AssetFactory;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

final class AssetFactoryTest extends TestCase
{
    /** @test */
    function it_creates_asset()
    {
        $factory = new AssetFactory();
        $asset = $factory->create('AAPL', 'Apple Inc.');

        Assert::uuid($asset->getId());
        $this->assertSame('AAPL', $asset->getTicker());
        $this->assertSame('Apple Inc.', $asset->getName());
    }

    /** @test */
    function it_creates_asset_without_name()
    {
        $factory = new AssetFactory();
        $asset = $factory->create('AAPL');

        Assert::uuid($asset->getId());
        $this->assertSame('AAPL', $asset->getTicker());
        $this->assertSame('AAPL', $asset->getName());
    }
}
