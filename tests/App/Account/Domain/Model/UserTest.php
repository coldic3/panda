<?php

declare(strict_types=1);

namespace App\Tests\App\Account\Domain\Model;

use App\Account\Domain\Model\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /** @test */
    function it_compares_to_another_user_by_id()
    {
        $user = new User('panda@example.com');

        $sameUser = clone $user;
        $sameUser->setEmail('dangerous.bear@example.com');

        $otherUser = new User('real.dangerous.bear@example.com');

        $this->assertTrue($user->compare($sameUser));
        $this->assertFalse($user->compare($otherUser));
    }
}
