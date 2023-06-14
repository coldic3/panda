<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioCommand;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Shared\Application\Command\CommandBusInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;
use Webmozart\Assert\Assert;

class PortfolioContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly AssetContext $assetContext,
        private readonly PortfolioFactoryInterface $portfolioFactory,
        private readonly PortfolioItemFactoryInterface $portfolioItemFactory,
        private readonly PortfolioRepositoryInterface $portfolioRepository,
        private readonly CommandBusInterface $commandBus,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Given posiadam portfel inwestycyjny
     * @Given posiadam portfel o nazwie :name
     * @Given posiadam już domyślny portfel :name
     * @Given posiadam domyślny portfel o nazwie :name
     */
    function there_is_an_portfolio_with_name(string $name = 'Default Portfolio')
    {
        Assert::isInstanceOf(
            $portfolio = $this->commandBus->dispatch(new CreatePortfolioCommand($name)),
            PortfolioInterface::class,
        );

        $this->clipboard->copy('portfolio', $portfolio);
    }

    /**
     * @Given /^posiadam (\d+) ([^"]+) w portfelu inwestycyjnym$/
     * @Given /^posiadam (\d+) akcji spółki "([^"]+)" o nazwie "([^"]+)"$/
     */
    function there_is_an_asset_with_ticker_and_name_and_long_quantity(float $quantity, string $ticker, ?string $name = null)
    {
        $this->assetContext->there_is_an_asset_with_ticker_and_name($ticker, $name ?? $ticker);

        $portfolio = $this->clipboard->paste('portfolio');

        if (null === $portfolio) {
            return;
        }

        $portfolioItem = $this->portfolioItemFactory->create($ticker, $name ?? $ticker, $portfolio);

        // FIXME: This is a temporary solution for currencies with fractional units.
        if ('PLN' === $ticker) {
            $quantity = ($quantity * 100);
        }

        $quantity = (int) $quantity;

        $portfolioItem->addLongQuantity($quantity);
        $this->portfolioRepository->save($portfolio);
        $this->entityManager->flush();
    }

    /**
     * @Given /^posiadam aktywo "([^"]+)" o nazwie "([^"]+)" w ilości (\d+)$/
     */
    function there_is_an_asset_with_ticker_and_name_and_long_quantity_2(string $ticker, string $name, float $quantity)
    {
        $this->there_is_an_asset_with_ticker_and_name_and_long_quantity($quantity, $ticker, $name);
    }

    /**
     * @Given /^posiadam krótką pozycję na aktywie "([^"]+)" o nazwie "([^"]+)" w ilości (\d+)$/
     */
    function there_is_an_asset_with_ticker_and_name_and_short_quantity(string $ticker, string $name, float $quantity)
    {
        $this->assetContext->there_is_an_asset_with_ticker_and_name($ticker, $name);

        $portfolioItem = $this->portfolioItemFactory->create(
            $ticker,
            $name,
            $portfolio = $this->clipboard->paste('portfolio')
        );

        // FIXME: This is a temporary solution for currencies with fractional units.
        if ('PLN' === $ticker) {
            $quantity = ($quantity * 100);
        }

        $quantity = (int) $quantity;

        $portfolioItem->addShortQuantity($quantity);
        $this->portfolioRepository->save($portfolio);
        $this->entityManager->flush();
    }
}
