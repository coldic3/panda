<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Trade\Application\Command\Asset\CreateAssetCommand;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;

readonly class AssetContext implements Context
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository,
        private EntityManagerInterface $entityManager,
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @Given posiadam aktywo :ticker o nazwie :name
     */
    function there_is_an_asset_with_ticker_and_name(string $ticker, string $name)
    {
        $this->commandBus->dispatch(new CreateAssetCommand($ticker, $name));
    }

    /**
     * @Given /^(użytkownik "[^"]+") posiada aktywo "([^"]+)" o nazwie "([^"]+)"$/
     */
    function the_user_has_an_asset_with_ticker_and_name(UserInterface $user, string $ticker, string $name)
    {
        /** @var AssetInterface $asset */
        $asset = $this->commandBus->dispatch(new CreateAssetCommand($ticker, $name));

        $asset->setOwnedBy($user);

        $this->assetRepository->save($asset);
        $this->entityManager->flush();
    }
}
