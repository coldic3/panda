<?php

declare(strict_types=1);

namespace Panda\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class LayersSeparationTest
{
    private array $applicationLayerSelectors = [];
    private array $domainLayerSelectors = [];
    private array $infrastructureLayerSelectors = [];

    public function test_domain_does_not_depend_on_other_layers(): Rule
    {
        $this->findAllLayers();

        return PHPat::rule()
            ->classes(...$this->domainLayerSelectors)
            ->shouldNotDependOn()
            ->classes(...$this->applicationLayerSelectors, ...$this->infrastructureLayerSelectors);
    }

    public function test_domain_does_not_depend_on_doctrine(): Rule
    {
        $this->findAllLayers();

        return PHPat::rule()
            ->classes(...$this->domainLayerSelectors)
            ->shouldNotDependOn()
            ->classes(Selector::namespace('Doctrine'))

            // FIXME: requires too much effort to get rid of these dependencies for now
            ->excluding(
                Selector::classname('Doctrine\Common\Collections\ArrayCollection'),
                Selector::classname('Doctrine\Common\Collections\Collection'),
            );
    }

    public function test_application_does_not_depend_on_infrastructure_layer(): Rule
    {
        $this->findAllLayers();

        return PHPat::rule()
            ->classes(...$this->applicationLayerSelectors)
            ->shouldNotDependOn()
            ->classes(...$this->infrastructureLayerSelectors);
    }

    private function findAllLayers(): void
    {
        $boundedContextsFinder = new Finder();
        $boundedContextsFinder->directories()->in(__DIR__.'/../../src')->depth(0);

        $this->applicationLayerSelectors = [];
        $this->domainLayerSelectors = [];
        $this->infrastructureLayerSelectors = [];

        /** @var SplFileInfo $boundedContext */
        foreach ($boundedContextsFinder as $boundedContext) {
            $this->applicationLayerSelectors[] = Selector::namespace(sprintf('Panda\\%s\\Application', $boundedContext->getRelativePathname()));
            $this->domainLayerSelectors[] = Selector::namespace(sprintf('Panda\\%s\\Domain', $boundedContext->getRelativePathname()));
            $this->infrastructureLayerSelectors[] = Selector::namespace(sprintf('Panda\\%s\\Infrastructure', $boundedContext->getRelativePathname()));
        }
    }
}
