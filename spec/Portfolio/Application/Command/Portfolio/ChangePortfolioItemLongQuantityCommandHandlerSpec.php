<?php

namespace spec\Panda\Portfolio\Application\Command\Portfolio;

use ApiPlatform\Validator\ValidatorInterface;
use Panda\Core\Application\Command\CommandHandlerInterface;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommand;
use Panda\Portfolio\Application\Command\Portfolio\ChangePortfolioItemLongQuantityCommandHandler;
use Panda\Portfolio\Application\Exception\PortfolioItemWithTickerNotFoundException;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Panda\PortfolioOHS\Application\Resolver\PortfolioResolverInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class ChangePortfolioItemLongQuantityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        PortfolioResolverInterface $portfolioResolver,
        ValidatorInterface $validator,
    ) {
        $this->beConstructedWith($portfolioItemRepository, $portfolioResolver, $validator);
    }

    function it_is_change_portfolio_item_long_quantity_command_handler()
    {
        $this->shouldHaveType(ChangePortfolioItemLongQuantityCommandHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    function it_adds_long_quantity_to_already_created_portfolio_item(
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        PortfolioResolverInterface $portfolioResolver,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $portfolioItem,
    ) {
        $portfolioResolver->resolve()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn($portfolioItem);

        $portfolioItem->addLongQuantity(10)->shouldBeCalledOnce();

        $validator->validate($portfolioItem, ['groups' => ['panda:update']])->shouldBeCalledOnce();
        $portfolioItemRepository->save($portfolioItem)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', 10));
    }

    function it_removes_long_quantity_from_already_created_portfolio_item(
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        PortfolioResolverInterface $portfolioResolver,
        ValidatorInterface $validator,
        PortfolioInterface $portfolio,
        PortfolioItemInterface $portfolioItem,
    ) {
        $portfolioResolver->resolve()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn($portfolioItem);

        $portfolioItem->removeLongQuantity(10)->shouldBeCalledOnce();

        $validator->validate($portfolioItem, ['groups' => ['panda:update']])->shouldBeCalledOnce();
        $portfolioItemRepository->save($portfolioItem)->shouldBeCalledOnce();

        $this(new ChangePortfolioItemLongQuantityCommand('ACM', -10));
    }

    function it_throws_exception_if_portfolio_item_does_not_exist(
        PortfolioItemRepositoryInterface $portfolioItemRepository,
        PortfolioResolverInterface $portfolioResolver,
        PortfolioInterface $portfolio,
    ) {
        $portfolioResolver->resolve()->willReturn($portfolio);

        $portfolioItemRepository
            ->findByTickerWithinPortfolio('ACM', $portfolio)
            ->willReturn(null);

        $portfolio->getId()->willReturn(Uuid::v4());

        $this->shouldThrow(PortfolioItemWithTickerNotFoundException::class)
            ->during('__invoke', [new ChangePortfolioItemLongQuantityCommand('ACM', 10)]);
    }
}
