<?php

declare(strict_types=1);

namespace App\Reception\Infrastructure\Doctrine\Orm;

use App\Reception\Domain\Model\Greeting;
use App\Reception\Domain\Repository\GreetingRepositoryInterface;
use App\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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
}
