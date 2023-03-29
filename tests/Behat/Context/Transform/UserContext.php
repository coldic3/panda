<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @Transform /^użytkownik "([^"]+)"$/
     */
    public function user(string $email): UserInterface
    {
        Assert::isInstanceOf(
            $user = $this->userRepository->findByEmail($email),
            UserInterface::class
        );

        return $user;
    }
}
