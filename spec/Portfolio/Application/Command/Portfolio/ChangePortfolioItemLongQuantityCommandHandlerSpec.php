<?php

namespace spec\Panda\Portfolio\Application\Command\Portfolio;

use Doctrine\Common\Collections\ArrayCollection;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommand;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommandHandler;
use Panda\Portfolio\Domain\Factory\PortfolioItemFactoryInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use Panda\Shared\Application\Command\CommandHandlerInterface;
use PhpSpec\ObjectBehavior;

class ChangePortfolioItemLongQuantityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemFactoryInterface $portfolioItemFactory,
    ) {
        $this->beConstructedWith($portfolioRepository, $portfolioItemFactory);
    }

    function it_is_change_portfolio_item_long_quantity_command_handler()
    {
        $this->shouldHaveType(ChangePortfolioItemLongQuantityCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_adds_long_quantity_to_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $somePortfolioItem,
        PortfolioItemInterface $portfolioItemWeAreLookingFor,
        PortfolioItemInterface $someOtherPortfolioItem,
        ResourceInterface $somePortfolioItemResource,
        ResourceInterface $portfolioItemWeAreLookingForResource,
        ResourceInterface $someOtherPortfolioItemResource,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolio
            ->getItems()
            ->willReturn(new ArrayCollection([
                $somePortfolioItem->getWrappedObject(),
                $portfolioItemWeAreLookingFor->getWrappedObject(),
                $someOtherPortfolioItem->getWrappedObject(),
            ]));

        $somePortfolioItem->getResource()->willReturn($somePortfolioItemResource);
        $portfolioItemWeAreLookingFor->getResource()->willReturn($portfolioItemWeAreLookingForResource);
        $someOtherPortfolioItem->getResource()->willReturn($someOtherPortfolioItemResource);

        $somePortfolioItemResource->getTicker()->willReturn('ABC');
        $portfolioItemWeAreLookingForResource->getTicker()->willReturn('ACM');
        $someOtherPortfolioItemResource->getTicker()->willReturn('XYZ');

        $portfolioItemWeAreLookingFor->addLongQuantity(10)->shouldBeCalledOnce();

        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', 10));
    }

    function it_adds_long_quantity_to_portfolio_item_that_does_not_exist_yet(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemFactoryInterface $portfolioItemFactory,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $somePortfolioItem,
        PortfolioItemInterface $portfolioItemWeAreLookingFor,
        PortfolioItemInterface $someOtherPortfolioItem,
        ResourceInterface $somePortfolioItemResource,
        ResourceInterface $someOtherPortfolioItemResource,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolio
            ->getItems()
            ->willReturn(new ArrayCollection([
                $somePortfolioItem->getWrappedObject(),
                $someOtherPortfolioItem->getWrappedObject(),
            ]));

        $somePortfolioItem->getResource()->willReturn($somePortfolioItemResource);
        $someOtherPortfolioItem->getResource()->willReturn($someOtherPortfolioItemResource);

        $somePortfolioItemResource->getTicker()->willReturn('ABC');
        $someOtherPortfolioItemResource->getTicker()->willReturn('XYZ');

        $portfolioItemFactory
            ->create('ACM', 'ACM', $portfolio)
            ->willReturn($portfolioItemWeAreLookingFor);

        $portfolioItemWeAreLookingFor->addLongQuantity(10)->shouldBeCalledOnce();

        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', 10));
    }

    function it_removes_long_quantity_from_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $somePortfolioItem,
        PortfolioItemInterface $portfolioItemWeAreLookingFor,
        PortfolioItemInterface $someOtherPortfolioItem,
        ResourceInterface $somePortfolioItemResource,
        ResourceInterface $portfolioItemWeAreLookingForResource,
        ResourceInterface $someOtherPortfolioItemResource,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolio
            ->getItems()
            ->willReturn(new ArrayCollection([
                $somePortfolioItem->getWrappedObject(),
                $portfolioItemWeAreLookingFor->getWrappedObject(),
                $someOtherPortfolioItem->getWrappedObject(),
            ]));

        $somePortfolioItem->getResource()->willReturn($somePortfolioItemResource);
        $portfolioItemWeAreLookingFor->getResource()->willReturn($portfolioItemWeAreLookingForResource);
        $someOtherPortfolioItem->getResource()->willReturn($someOtherPortfolioItemResource);

        $somePortfolioItemResource->getTicker()->willReturn('ABC');
        $portfolioItemWeAreLookingForResource->getTicker()->willReturn('ACM');
        $someOtherPortfolioItemResource->getTicker()->willReturn('XYZ');

        $portfolioItemWeAreLookingFor->removeLongQuantity(10)->shouldBeCalledOnce();

        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', -10));
    }

    function it_removes_long_quantity_from_portfolio_item_that_does_not_exist_yet(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemFactoryInterface $portfolioItemFactory,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $somePortfolioItem,
        PortfolioItemInterface $portfolioItemWeAreLookingFor,
        PortfolioItemInterface $someOtherPortfolioItem,
        ResourceInterface $somePortfolioItemResource,
        ResourceInterface $someOtherPortfolioItemResource,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolio
            ->getItems()
            ->willReturn(new ArrayCollection([
                $somePortfolioItem->getWrappedObject(),
                $someOtherPortfolioItem->getWrappedObject(),
            ]));

        $somePortfolioItem->getResource()->willReturn($somePortfolioItemResource);
        $someOtherPortfolioItem->getResource()->willReturn($someOtherPortfolioItemResource);

        $somePortfolioItemResource->getTicker()->willReturn('ABC');
        $someOtherPortfolioItemResource->getTicker()->willReturn('XYZ');

        $portfolioItemFactory
            ->create('ACM', 'ACM', $portfolio)
            ->willReturn($portfolioItemWeAreLookingFor);

        $portfolioItemWeAreLookingFor->removeLongQuantity(10)->shouldBeCalledOnce();

        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', -10));
    }

    function it_does_nothing_if_default_portfolio_does_not_exist(PortfolioRepositoryInterface $portfolioRepository)
    {
        $portfolioRepository->findDefault()->willReturn(null);

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', -10));
    }
}
