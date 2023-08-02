<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\ValueObject\Resource;

final readonly class PortfolioFactory implements PortfolioFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function create(
        string $name,
        string $mainResourceTicker,
        string $mainResourceName,
        bool $default = false,
        OwnerInterface $owner = null
    ): PortfolioInterface {
        $portfolio = new Portfolio($name, new Resource($mainResourceTicker, $mainResourceName), $default);

        if (null !== $owner) {
            $portfolio->setOwnedBy($owner);

            return $portfolio;
        }

        $owner = $this->authorizedUserProvider->provide();

        $portfolio->setOwnedBy($owner);

        return $portfolio;
    }
}
