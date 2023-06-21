<?php

namespace spec\Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommand;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommandHandler;
use Panda\Portfolio\Application\Exception\DefaultPortfolioNotFoundException;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Model\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class ChangePortfolioItemLongQuantityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        ValidatorInterface $validator,
    ) {
        $this->beConstructedWith($portfolioRepository, $portfolioItemRepository, $validator);
    }

    function it_is_change_portfolio_item_long_quantity_command_handler()
    {
        $this->shouldHaveType(ChangePortfolioItemLongQuantityCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_adds_long_quantity_to_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $portfolioItem,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn($portfolioItem);

        $portfolioItem->addLongQuantity(10)->shouldBeCalledOnce();

        $validator->validate($portfolio, ['groups' => ['panda:update']])->shouldBeCalledOnce();
        $portfolioRepository->save($portfolio)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', 10));
    }

    function it_removes_long_quantity_from_already_created_portfolio_item(
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $portfolioItem,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn($portfolioItem);

        $portfolioItem->removeLongQuantity(10)->shouldBeCalledOnce();

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
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
    ) {
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn(null);

        $portfolio->getId()->willReturn(Uuid::v4());

        $validator->validate($portfolio, ['groups' => ['panda:update']])->shouldNotBeCalled();
        $portfolioRepository->save($portfolio)->shouldNotBeCalled();

        $this->shouldThrow(PortfolioItemWithTickerNotFoundException::class)
            ->during('__invoke', [new ChangePortfolioItemLongQuantityCommand('ACM', 10)]);
    }
}
