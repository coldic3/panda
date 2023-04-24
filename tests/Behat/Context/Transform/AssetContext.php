<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Webmozart\Assert\Assert;

class AssetContext implements Context
{
    public function __construct(private readonly AssetRepositoryInterface $assetRepository)
    {
    }

    /**
     * @Transform /^aktywo "([^"]+)"$/
     * @Transform /^spółki "([^"]+)"$/
     * @Transform :asset
     * @Transform :grossAsset
     * @Transform :netAsset
     */
    public function asset(string $ticker): AssetInterface
    {
        Assert::isInstanceOf(
            $asset = $this->assetRepository->filterBy('ticker', $ticker)->item(),
            AssetInterface::class
        );

        return $asset;
    }
}
