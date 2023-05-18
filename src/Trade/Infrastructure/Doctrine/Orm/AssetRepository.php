<?php

declare(strict_types=1);

namespace Panda\Trade\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\AccountOHS\Domain\Model\Owner\OwnerInterface;
use Panda\Shared\Domain\Repository\QueryInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Panda\Trade\Domain\Model\Asset\Asset;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Panda\Trade\Domain\Repository\AssetRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class AssetRepository extends DoctrineRepository implements AssetRepositoryInterface
{
    private const ENTITY_CLASS = Asset::class;
    private const ALIAS = 'asset';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(AssetInterface $asset): void
    {
        $this->em->persist($asset);
    }

    public function remove(AssetInterface $asset): void
    {
        $this->em->remove($asset);
    }

    public function findById(Uuid $id): ?AssetInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function defaultQuery(OwnerInterface $owner): QueryInterface
    {
        return new Query\DefaultAssetQuery($owner);
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
