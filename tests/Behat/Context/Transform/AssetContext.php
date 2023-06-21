<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Trade\Domain\Model\Asset\Asset;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Webmozart\Assert\Assert;

class AssetContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @Transform /^aktywo "([^"]+)"$/
     * @Transform /^aktywa "([^"]+)"$/
     * @Transform /^spółki "([^"]+)"$/
     * @Transform :asset
     * @Transform :fromAsset
     * @Transform :toAsset
     * @Transform :adjustmentAsset
     * @Transform :grossAsset
     * @Transform :netAsset
     */
    public function asset(string $ticker): AssetInterface
    {
        Assert::isInstanceOf(
            $asset = $this->entityManager->getRepository(Asset::class)->findOneBy(['ticker' => $ticker]),
            AssetInterface::class
        );

        return $asset;
    }
}
