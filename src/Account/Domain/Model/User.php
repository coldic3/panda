<?php

namespace Panda\Account\Domain\Model;

use Symfony\Component\Uid\Uuid;

class User implements UserInterface
{
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

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
    }

    public function compare(UserInterface $user): bool
    {
        return $this->getId() === $user->getId();
    }
}
