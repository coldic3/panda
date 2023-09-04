<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\Core\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioInterface;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItem;
use Panda\Portfolio\Domain\Model\Portfolio\PortfolioItemInterface;
use Panda\Portfolio\Domain\Repository\PortfolioItemRepositoryInterface;
use Webmozart\Assert\Assert;

final class PortfolioItemRepository extends DoctrineRepository implements PortfolioItemRepositoryInterface
{
    private const ENTITY_CLASS = PortfolioItem::class;
    private const ALIAS = 'portfolioItem';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(PortfolioItemInterface $portfolioItem): void
    {
        $this->em->persist($portfolioItem);
    }

    public function findByTickerWithinPortfolio(string $ticker, PortfolioInterface $portfolio): ?PortfolioItemInterface
    {
        $portfolioItem = $this->em
            ->createQueryBuilder()
            ->select('o')
            ->from(self::ENTITY_CLASS, 'o')
            ->where('o.resource.ticker = :ticker')
            ->andWhere('o.portfolio = :portfolio')
            ->setParameter('ticker', $ticker)
            ->setParameter('portfolio', $portfolio)
            ->getQuery()
            ->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($portfolioItem, PortfolioItemInterface::class);

        return $portfolioItem;
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
