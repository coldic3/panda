<?php

namespace spec\App\Shared\Infrastructure\Doctrine\Orm;

use App\Shared\Infrastructure\Doctrine\Orm\DoctrinePaginator;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PhpSpec\ObjectBehavior;

class DoctrinePaginatorSpec extends ObjectBehavior
{
    function it_is_initializable(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $this->constructPaginator($paginator, $entityManager, $configuration, 0, 10);

        $this->shouldHaveType(DoctrinePaginator::class);
    }

    function it_is_not_initializable_if_max_results_is_null(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $entityManager->getConfiguration()->willReturn($configuration);

        $query = new Query($entityManager->getWrappedObject());
        $query->setMaxResults(null);

        $paginator->getQuery()->willReturn($query)->shouldBeCalledOnce();

        $this->shouldThrow(\InvalidArgumentException::class)->during('__construct', [$paginator]);
    }

    function it_gets_current_page(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $this->constructPaginator($paginator, $entityManager, $configuration, 190, 20);

        $this->getCurrentPage()->shouldReturn(10);
    }

    function it_gets_current_page_as_a_first_page_if_first_result_is_zero(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $this->constructPaginator($paginator, $entityManager, $configuration, 0, 10);

        $this->getCurrentPage()->shouldReturn(1);
    }

    function it_gets_current_page_as_a_next_page_if_limit_reached(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $this->constructPaginator($paginator, $entityManager, $configuration, 200, 10);

        $this->getCurrentPage()->shouldReturn(21);
    }

    function it_gets_last_page(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $paginator->count()->willReturn(40);

        $this->constructPaginator($paginator, $entityManager, $configuration, 150, 20);

        $this->getLastPage()->shouldReturn(2);
    }

    function it_gets_last_page_and_takes_leftovers_into_account(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $paginator->count()->willReturn(45);

        $this->constructPaginator($paginator, $entityManager, $configuration, 150, 20);

        $this->getLastPage()->shouldReturn(3);
    }

    function it_gets_last_page_as_a_first_page_if_max_results_is_a_negative_number(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $paginator->count()->willReturn(45);

        $this->constructPaginator($paginator, $entityManager, $configuration, 150, -20);
        $this->getLastPage()->shouldReturn(1);
    }

    function it_gets_last_page_as_a_first_page_if_max_results_is_zero(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
    ) {
        $this->constructPaginator($paginator, $entityManager, $configuration, 10, 0);

        $this->getLastPage()->shouldReturn(1);
    }

    private function constructPaginator(
        Paginator $paginator,
        EntityManagerInterface $entityManager,
        Configuration $configuration,
        int|null $firstResult,
        int|null $maxResults,
    ): void {
        $entityManager->getConfiguration()->willReturn($configuration);

        $query = new Query($entityManager->getWrappedObject());

        // Query::firstResult is set to 0 by default. It is not possible to set it to null.
        $query->setFirstResult($firstResult);
        $query->setMaxResults($maxResults);

        $paginator->getQuery()->willReturn($query)->shouldBeCalledOnce();

        $this->beConstructedWith($paginator);
    }
}
