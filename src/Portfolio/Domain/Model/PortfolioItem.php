<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Model;

use Panda\Portfolio\Domain\Exception\NegativeQuantityException;
use Panda\Portfolio\Domain\Exception\NegativeTotalQuantityException;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use Panda\Shared\Domain\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class PortfolioItem implements PortfolioItemInterface
{
    use TimestampableTrait;

    private Uuid $id;
    private int $quantity = 0;

    public function __construct(
        private ResourceInterface $resource,
        private PortfolioInterface $portfolio,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function setResource(ResourceInterface $resource): void
    {
        $this->resource = $resource;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function addQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        $this->quantity += $quantity;
    }

    public function removeQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        if ($quantity > $this->quantity) {
            throw new NegativeTotalQuantityException($quantity);
        }

        $this->quantity -= $quantity;
    }

    public function getPortfolio(): PortfolioInterface
    {
        return $this->portfolio;
    }
}
