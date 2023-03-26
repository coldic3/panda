<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Asset\Domain\Factory\AssetFactoryInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;

class AssetContext implements Context
{
    public function __construct(
        private readonly AssetFactoryInterface $assetFactory,
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Given posiadam aktywo :ticker o nazwie :name
     */
    function there_is_an_asset_with_ticker_and_name(string $ticker, string $name)
    {
        $asset = $this->assetFactory->create($ticker, $name);

        $this->assetRepository->save($asset);
        $this->entityManager->flush();
    }
}
