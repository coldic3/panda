<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Model;

use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Shared\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class Asset implements AssetInterface
{
    use TimestampableTrait;

    public readonly Uuid $id;

    private ?OwnerInterface $owner = null;

    public function __construct(private string $ticker, private string $name)
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTicker(): string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): void
    {
        $this->ticker = $ticker;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwnedBy(): ?OwnerInterface
    {
        return $this->owner;
    }

    public function setOwnedBy(OwnerInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function compare(ResourceInterface $resource): bool
    {
        return $this->getId() === $resource->getId();
    }
}
