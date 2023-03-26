<?php

declare(strict_types=1);

namespace Panda\Asset\Domain\Model;

use Panda\Shared\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class Asset implements AssetInterface
{
    use TimestampableTrait;

    public readonly Uuid $id;

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
}
