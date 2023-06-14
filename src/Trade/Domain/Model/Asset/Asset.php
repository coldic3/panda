<?php

declare(strict_types=1);

namespace Panda\Trade\Domain\Model\Asset;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class Asset implements AssetInterface
{
    use TimestampableTrait;

    private Uuid $id;
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

    public function compare(AssetInterface $asset): bool
    {
        return $this->getId() === $asset->getId();
    }
}
