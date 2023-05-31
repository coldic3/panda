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
    private int $longQuantity = 0;
    private int $shortQuantity = 0;

    public function __construct(
        private ResourceInterface $resource,
        private PortfolioInterface $portfolio,
    ) {
        $this->id = Uuid::v4();

        $this->portfolio->addItem($this);
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

    public function getLongQuantity(): int
    {
        return $this->longQuantity;
    }

    public function addLongQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        $this->longQuantity += $quantity;
    }

    public function removeLongQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        if ($quantity > $this->longQuantity) {
            throw new NegativeTotalQuantityException($quantity);
        }

        $this->longQuantity -= $quantity;
    }

    public function getShortQuantity(): int
    {
        return $this->shortQuantity;
    }

    public function addShortQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        $this->shortQuantity += $quantity;
    }

    public function removeShortQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new NegativeQuantityException($quantity);
        }

        if ($quantity > $this->shortQuantity) {
            throw new NegativeTotalQuantityException($quantity);
        }

        $this->shortQuantity -= $quantity;
    }

    public function getPortfolio(): PortfolioInterface
    {
        return $this->portfolio;
    }
}
