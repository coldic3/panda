<?php

declare(strict_types=1);

namespace Panda\Tests\App\Trade\Domain\Factory;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Trade\Domain\Factory\OperationFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Webmozart\Assert\Assert;

final class OperationFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_operation()
    {
        $resource = $this->prophesize(ResourceInterface::class);

        $factory = new OperationFactory();
        $operation = $factory->create($resource->reveal(), 10);

        Assert::uuid($operation->getId());
        $this->assertInstanceOf(ResourceInterface::class, $operation->getResource());
        $this->assertSame(10, $operation->getQuantity());
    }
}
