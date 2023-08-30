<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Panda\Core\Application\Command\CommandBusInterface;
use Panda\Portfolio\Application\Command\Portfolio\CreatePortfolioCommand;
use Panda\Portfolio\Domain\Factory\PortfolioFactoryInterface;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
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
     * @Given posiadam portfel inwestycyjny w walucie :ticker
     */
    function there_is_an_portfolio_with_name(string $name = 'Default Portfolio', string $ticker = 'PLN')
    {
        Assert::isInstanceOf(
            $portfolio = $this->commandBus->dispatch(new CreatePortfolioCommand($name, $ticker, $ticker)),
            PortfolioInterface::class,
        );

        $this->clipboard->copy('portfolio', $portfolio);
    }

    /**
     * @Given /^posiadam (\d+) ([^"]+) w portfelu inwestycyjnym$/
     * @Given /^posiadam (\d+) akcji spółki "([^"]+)" o nazwie "([^"]+)"$/
     */
    function there_is_an_asset_with_ticker_and_name_and_long_quantity(float $quantity, string $ticker, string $name = null)
    {
        $this->assetContext->there_is_an_asset_with_ticker_and_name($ticker, $name ?? $ticker);

        Assert::nullOrIsInstanceOf(
            $portfolio = $this->clipboard->paste('portfolio'),
            PortfolioInterface::class,
        );

        if (null === $portfolio) {
            return;
        }

        $portfolioItem = $portfolio->getItems()->filter(
            fn (PortfolioItemInterface $item) => $item->getResource()->getTicker() === $ticker
        )->first();

        // FIXME: This is a temporary solution for currencies with fractional units.
        if ('PLN' === $ticker) {
            $quantity = (int) ($quantity * 100);
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

        Assert::nullOrIsInstanceOf(
            $portfolio = $this->clipboard->paste('portfolio'),
            PortfolioInterface::class,
        );

        if (null === $portfolio) {
            return;
        }

        $portfolioItem = $portfolio->getItems()->filter(
            fn (PortfolioItemInterface $item) => $item->getResource()->getTicker() === $ticker
        )->first();

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
