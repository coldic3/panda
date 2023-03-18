<?php

declare(strict_types=1);

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class BoundedContextsSeparationTest
{
    public function test_account_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\\Account'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Account', 'Shared']));
    }

    public function test_shared_bounded_context_does_not_depend_on_other_bounded_contexts(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\\Shared'))
            ->shouldNotDependOn()
            ->classes(...$this->findBoundedContextsExcept(['Shared']));
    }

    private function findBoundedContextsExcept(array $exceptions): array
    {
        $boundedContextsFinder = new Finder();
        $boundedContextsFinder->directories()->in(__DIR__ . '/../../src')->depth(0);

        $boundedContextSelectors = [];

        /** @var SplFileInfo $boundedContext */
        foreach ($boundedContextsFinder as $boundedContext) {
            if (in_array($boundedContext->getRelativePathname(), $exceptions, true)) {
                continue;
            }

            $boundedContextSelectors[] = Selector::namespace(sprintf('App\\%s', $boundedContext->getRelativePathname()));
        }

        return $boundedContextSelectors;
    }
}
