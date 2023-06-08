<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Webmozart\Assert\Assert;

class PortfolioContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @Transform /^portfel "([^"]+)"$/
     */
    public function portfolio(string $name): PortfolioInterface
    {
        Assert::isInstanceOf(
            $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy(['name' => $name]),
            PortfolioInterface::class
        );

        return $portfolio;
    }
}
