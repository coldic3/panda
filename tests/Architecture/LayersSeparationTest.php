<?php

declare(strict_types=1);

namespace Panda\Tests\Architecture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Uid\Uuid;

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

    public function test_domain_does_not_depend_on_vendor(): Rule
    {
        $this->findAllLayers();

        return PHPat::rule()
            ->classes(...$this->domainLayerSelectors)
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Panda'),

                // Allowed 3rd party classes
                Selector::classname(Uuid::class),
                Selector::classname(Collection::class),
                Selector::classname(ArrayCollection::class),

                // PHP root namespace
                Selector::classname(\BackedEnum::class),
                Selector::classname(\Countable::class),
                Selector::classname(\DateTimeImmutable::class),
                Selector::classname(\DateTimeInterface::class),
                Selector::classname(\Exception::class),
                Selector::classname(\InvalidArgumentException::class),
                Selector::classname(\Iterator::class),
                Selector::classname(\IteratorAggregate::class),
                Selector::classname(\Throwable::class),
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
