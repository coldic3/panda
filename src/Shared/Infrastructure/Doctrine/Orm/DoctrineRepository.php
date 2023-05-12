<?php

declare(strict_types=1);

namespace Panda\Shared\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Panda\Shared\Domain\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

abstract class DoctrineRepository implements RepositoryInterface
{
    private QueryBuilder $queryBuilder;

    public function __construct(
        protected EntityManagerInterface $em,
        string $entityClass,
        string $alias,
    ) {
        $this->queryBuilder = $this->em->createQueryBuilder()
            ->select($alias)
            ->from($entityClass, $alias);
    }

    public function collection(): CollectionIteratorInterface
    {
        return (new DoctrineCollectionIterator($this->query()))->withoutPagination();
    }

    public function pagination(int $page = null, int $itemsPerPage = null): CollectionIteratorInterface
    {
        Assert::notNull($page);
        Assert::notNull($itemsPerPage);

        return (new DoctrineCollectionIterator($this->query()))->withPagination($page, $itemsPerPage);
    }

    public function item(): ?object
    {
        $item = $this->query()->setMaxResults(1)->getQuery()->getOneOrNullResult();

        if (null === $item) {
            return null;
        }

        Assert::isInstanceOf($item, $this->getEntityClass());

        return $item;
    }

    public function filterBy(string $fieldName, mixed $value, bool $strict = true): static
    {
        $alias = $this->getAlias();

        $operator = '=';

        if (!$strict) {
            $value = '%'.$value.'%';
            $operator = 'LIKE';
        }

        return $this->filter(static function (QueryBuilder $qb) use ($alias, $fieldName, $operator, $value): void {
            $qb->where(sprintf('%s.%s %s :value', $alias, $fieldName, $operator))
                ->setParameter('value', $value);
        });
    }

    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function buildOnto(callable $buildOnto): static
    {
        return $this->filter($buildOnto);
    }

    protected function query(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    /** @return class-string */
    abstract protected function getEntityClass(): string;

    abstract protected function getAlias(): string;
}
