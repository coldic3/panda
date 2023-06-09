<?php

declare(strict_types=1);

namespace Panda\Portfolio\Domain\Factory;

use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\AccountOHS\Domain\Provider\AuthorizedUserProviderInterface;
use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Portfolio\Domain\Model\PortfolioInterface;

final readonly class PortfolioFactory implements PortfolioFactoryInterface
{
    public function __construct(private AuthorizedUserProviderInterface $authorizedUserProvider)
    {
    }

    public function create(string $name, bool $default = false, ?OwnerInterface $owner = null): PortfolioInterface
    {
        $portfolio = new Portfolio($name, $default);

        if (null !== $owner) {
            $portfolio->setOwnedBy($owner);

            return $portfolio;
        }

        $owner = $this->authorizedUserProvider->provide();

        $portfolio->setOwnedBy($owner);

        return $portfolio;
    }
}
