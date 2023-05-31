<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\Portfolio\Domain\Model\Portfolio;
use Panda\Portfolio\Domain\Model\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Symfony\Component\Uid\Uuid;

final class PortfolioRepository extends DoctrineRepository implements PortfolioRepositoryInterface
{
    private const ENTITY_CLASS = Portfolio::class;
    private const ALIAS = 'portfolio';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(PortfolioInterface $portfolio): void
    {
        $this->em->persist($portfolio);
    }

    public function remove(PortfolioInterface $portfolio): void
    {
        $this->em->remove($portfolio);
    }

    public function findById(Uuid $id): ?PortfolioInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
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
