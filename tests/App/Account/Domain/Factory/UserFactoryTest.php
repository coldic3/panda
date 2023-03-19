<?php

declare(strict_types=1);

namespace Panda\Tests\App\Account\Domain\Factory;

use Panda\Account\Domain\Factory\UserFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Webmozart\Assert\Assert;

final class UserFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_creates_user_with_hashed_password()
    {
        $userPasswordHasher = $this->prophesize(UserPasswordHasherInterface::class);
        $userPasswordHasher->hashPassword(Argument::any(), 'I<3BambooShoots')->willReturn('khorisa');

        $factory = new UserFactory($userPasswordHasher->reveal());
        $user = $factory->create('panda@example.com', 'I<3BambooShoots');

        Assert::uuid($user->id);
        $this->assertSame('panda@example.com', $user->getEmail());
        $this->assertSame('khorisa', $user->getPassword());
    }

    /** @test */
    function it_creates_user_without_password()
    {
        $userPasswordHasher = $this->prophesize(UserPasswordHasherInterface::class);

        $factory = new UserFactory($userPasswordHasher->reveal());
        $user = $factory->create('panda@example.com');

        Assert::uuid($user->id);
        $this->assertSame('panda@example.com', $user->getEmail());
        $this->assertNull($user->getPassword());
    }
}
