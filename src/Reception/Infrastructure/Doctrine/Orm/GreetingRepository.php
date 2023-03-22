<?php

declare(strict_types=1);

namespace Panda\Reception\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Panda\Reception\Domain\Model\Greeting;
use Panda\Reception\Domain\Repository\GreetingRepositoryInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Symfony\Component\Uid\Uuid;

final class GreetingRepository extends DoctrineRepository implements GreetingRepositoryInterface
{
    private const ENTITY_CLASS = Greeting::class;
    private const ALIAS = 'greeting';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Greeting $greeting): void
    {
        $this->em->persist($greeting);
        $this->em->flush();
    }

    public function remove(Greeting $greeting): void
    {
        $this->em->remove($greeting);
        $this->em->flush();
    }

    public function findById(Uuid $id): ?Greeting
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function likeName(string $name): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($name): void {
            $qb->where(sprintf('%s.name LIKE :name', self::ALIAS))->setParameter('name', '%'.$name.'%');
        });
    }

    protected function getEntityClass(): string
    {
        return self::ENTITY_CLASS;
    }

    protected function getAlias(): string
    {
        return self::ALIAS;
    }
}
