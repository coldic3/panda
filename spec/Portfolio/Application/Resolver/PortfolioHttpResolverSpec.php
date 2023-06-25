<?php

namespace spec\Panda\Portfolio\Application\Resolver;

use Panda\Portfolio\Application\Exception\PortfolioNotFoundException;
use Panda\Portfolio\Application\Resolver\PortfolioHttpResolver;
use Panda\Portfolio\Application\Resolver\PortfolioResolverInterface;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

class PortfolioHttpResolverSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack, PortfolioRepositoryInterface $portfolioRepository)
    {
        $this->beConstructedWith($requestStack, $portfolioRepository);
    }

    function it_is_portfolio_http_resolver()
    {
        $this->shouldHaveType(PortfolioHttpResolver::class);
        $this->shouldImplement(PortfolioResolverInterface::class);
    }

    function it_resolves_default_portfolio_if_portfolio_id_not_passed(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
    ) {
        $request = new Request();

        $requestStack->getMainRequest()->willReturn($request);
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $this->resolve()->shouldReturn($portfolio);
    }

    function it_resolves_default_portfolio_if_portfolio_id_is_null(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
    ) {
        $request = new Request();
        $request->headers->set('X-Portfolio-Id', null);

        $requestStack->getMainRequest()->willReturn($request);
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $this->resolve()->shouldReturn($portfolio);
    }

    function it_resolves_default_portfolio_if_main_request_not_found(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
    ) {
        $requestStack->getMainRequest()->willReturn(null);
        $portfolioRepository->findDefault()->willReturn($portfolio);

        $this->resolve()->shouldReturn($portfolio);
    }

    function it_resolves_given_portfolio(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
        PortfolioInterface $portfolio,
    ) {
        $request = new Request();
        $request->headers->set('X-Portfolio-Id', '10daa5ca-6ce3-4597-be59-deee42b5ca14');

        $requestStack->getMainRequest()->willReturn($request);
        $portfolioRepository
            ->findById(Uuid::fromString('10daa5ca-6ce3-4597-be59-deee42b5ca14'))
            ->willReturn($portfolio);

        $this->resolve()->shouldReturn($portfolio);
    }

    function it_throws_exception_if_default_portfolio_not_found(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
    ) {
        $request = new Request();

        $requestStack->getMainRequest()->willReturn($request);
        $portfolioRepository->findDefault()->willReturn(null);

        $this->shouldThrow(PortfolioNotFoundException::class)->during('resolve');
    }

    function it_throws_exception_if_given_portfolio_not_found(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
    ) {
        $request = new Request();
        $request->headers->set('X-Portfolio-Id', '10daa5ca-6ce3-4597-be59-deee42b5ca14');

        $requestStack->getMainRequest()->willReturn($request);
        $portfolioRepository
            ->findById(Uuid::fromString('10daa5ca-6ce3-4597-be59-deee42b5ca14'))
            ->willReturn(null);

        $this->shouldThrow(PortfolioNotFoundException::class)->during('resolve');
    }

    function it_throws_exception_if_given_portfolio_is_an_invalid_uuid(
        RequestStack $requestStack,
        PortfolioRepositoryInterface $portfolioRepository,
    ) {
        $request = new Request();
        $request->headers->set('X-Portfolio-Id', 'invalid-uuid');

        $requestStack->getMainRequest()->willReturn($request);

        $this->shouldThrow(PortfolioNotFoundException::class)->during('resolve');
    }
}
