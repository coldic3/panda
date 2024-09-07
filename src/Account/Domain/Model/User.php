<?php

namespace Panda\Account\Domain\Model;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class User implements UserInterface
{
    use TimestampableTrait;

    private readonly Uuid $id;

    private ?string $password = null;

    public function __construct(private string $email)
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function compare(OwnerInterface $owner): bool
    {
        return $this->getId() === $owner->getId();
    }
}
