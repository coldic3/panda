<?php

declare(strict_types=1);

namespace Panda\Tests\App\Trade\Domain\Factory;

use Panda\Trade\Domain\Factory\OperationFactory;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Webmozart\Assert\Assert;

final class OperationFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_operation()
    {
        $resource = $this->prophesize(AssetInterface::class);

        $factory = new OperationFactory();
        $operation = $factory->create($resource->reveal(), 10);

        Assert::uuid($operation->getId());
        $this->assertInstanceOf(AssetInterface::class, $operation->getAsset());
        $this->assertSame(10, $operation->getQuantity());
    }
}
