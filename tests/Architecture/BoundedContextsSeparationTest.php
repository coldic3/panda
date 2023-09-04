<?php

declare(strict_types=1);

namespace Panda\Tests\Architecture;

use PHPat\Selector\ClassNamespace;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class BoundedContextsSeparationTest
{
    private const COUPLING_ALLOWED = ['Core'];

    public function test_account_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes($this->getBoundedContextClassNamespace('Account'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Account', 'AccountOHS', ...self::COUPLING_ALLOWED]));
    }

    public function test_exchange_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes($this->getBoundedContextClassNamespace('Exchange'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Exchange', 'AccountOHS', ...self::COUPLING_ALLOWED]));
    }

    public function test_portfolio_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes($this->getBoundedContextClassNamespace('Portfolio'))
            // FIXME: the below excluding is because of the report generation PoC, should be moved to separate BC
            ->excluding(Selector::namespace('Panda\Portfolio\Domain\ReportGenerator'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Portfolio', 'PortfolioOHS', 'AccountOHS', ...self::COUPLING_ALLOWED]));
    }

    public function test_trade_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes($this->getBoundedContextClassNamespace('Trade'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Trade', 'AccountOHS', ...self::COUPLING_ALLOWED]));
    }

    public function test_core_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes($this->getBoundedContextClassNamespace('Core'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Core']));
    }

    private function getBoundedContextClassNamespace(string $context): ClassNamespace
    {
        return Selector::namespace(sprintf('/^Panda\\\%s(\\\.*|)$/', $context), true);
    }

    private function findBoundedContextsExcept(array $exceptions = []): array
    {
        $boundedContextsFinder = new Finder();
        $boundedContextsFinder->directories()->in(__DIR__.'/../../src')->depth(0);

        $boundedContextSelectors = [];

        /** @var SplFileInfo $boundedContext */
        foreach ($boundedContextsFinder as $boundedContext) {
            if (in_array($boundedContext->getRelativePathname(), $exceptions, true)) {
                continue;
            }

            $boundedContextSelectors[] = Selector::namespace(sprintf('/^Panda\\\%s$/', $boundedContext->getRelativePathname()), true);
            $boundedContextSelectors[] = Selector::namespace(sprintf('/^Panda\\\%s\\\/', $boundedContext->getRelativePathname()), true);
        }

        return $boundedContextSelectors;
    }
}
