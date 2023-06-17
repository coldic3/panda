<?php

namespace spec\Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommand;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommandHandler;
use Panda\Portfolio\Application\Exception\DefaultPortfolioNotFoundException;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Portfolio\Domain\ValueObject\ResourceInterface;
use PhpSpec\ObjectBehavior;

class ChangePortfolioItemLongQuantityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        PortfolioRepositoryInterface $portfolioRepository,
        ValidatorInterface $validator,
    ) {
        $this->beConstructedWith($portfolioRepository, $validator);
    }

    function it_is_change_portfolio_item_long_quantity_command_handler()
    {
        $this->shouldHaveType(ChangePortfolioItemLongQuantityCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_adds_long_quantity_to_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        ValidatorInterface $validator,
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

        $validator->validate($portfolio, ['groups' => ['panda:update']])->shouldBeCalledOnce();
        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', 10));
    }

    function it_removes_long_quantity_from_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        ValidatorInterface $validator,
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

        $validator->validate($portfolio, ['groups' => ['panda:update']])->shouldBeCalledOnce();
        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', -10));
    }

    function it_throws_exception_if_default_portfolio_does_not_exist(PortfolioRepositoryInterface $portfolioRepository)
    {
        $portfolioRepository->findDefault()->willReturn(null);

        $this->shouldThrow(DefaultPortfolioNotFoundException::class)
            ->during('__invoke', [new ChangePortfolioItemLongQuantityCommand('ACM', 10)]);
    }

    function it_throws_exception_if_portfolio_item_does_not_exist(
        PortfolioRepositoryInterface $portfolioRepository,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $somePortfolioItem,
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

        $validator->validate($portfolio, ['groups' => ['panda:update']])->shouldNotBeCalled();
        $portfolioRepository->save($portfolio)->shouldNotBeCalled();

        $this->shouldThrow(PortfolioItemWithTickerNotFoundException::class)
            ->during('__invoke', [new ChangePortfolioItemLongQuantityCommand('ACM', 10)]);
    }
}
