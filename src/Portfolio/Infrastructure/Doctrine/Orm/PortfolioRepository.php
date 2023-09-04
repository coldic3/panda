<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Core\Domain\Repository\QueryInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Portfolio\Domain\Model\Portfolio\Portfolio;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Repository\PortfolioRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

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

    public function findDefault(): ?PortfolioInterface
    {
        $portfolio = $this->em
            ->createQueryBuilder()
            ->select('o')
            ->from(self::ENTITY_CLASS, 'o')
            ->where('o.default = true')
            ->getQuery()
            ->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($portfolio, PortfolioInterface::class);

        return $portfolio;
    }

    public function defaultExists(): bool
    {
        return (bool) $this->em
            ->createQueryBuilder()
            ->select('COUNT(o.id)')
            ->from(self::ENTITY_CLASS, 'o')
            ->where('o.default = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function defaultQuery(OwnerInterface $owner): QueryInterface
    {
        return new Query\DefaultPortfolioQuery($owner);
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
