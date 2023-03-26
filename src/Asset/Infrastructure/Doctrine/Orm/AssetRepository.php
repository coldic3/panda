<?php

declare(strict_types=1);

namespace Panda\Asset\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\Asset\Domain\Model\Asset;
use Panda\Asset\Domain\Model\AssetInterface;
use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
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

    protected function getEntityClass(): string
    {
        return self::ENTITY_CLASS;
    }

    protected function getAlias(): string
    {
        return self::ALIAS;
    }
}
