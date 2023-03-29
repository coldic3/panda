<?php

declare(strict_types=1);

namespace Panda\Asset\Application\Query\Asset;

use Panda\Asset\Domain\Repository\AssetRepositoryInterface;
use Panda\Contract\AggregateRoot\Owner\OwnerInterface;
use Panda\Shared\Application\Query\QueryHandlerInterface;
use Panda\Shared\Domain\Repository\CollectionIteratorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

final class FindAssetsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(FindAssetsQuery $query): ?CollectionIteratorInterface
    {
        Assert::isInstanceOf(
            $authorizedUser = $this->security->getUser(),
            OwnerInterface::class,
        );

        $assetRepository = $this->assetRepository->filterBy('owner', $authorizedUser);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $assetRepository->pagination($query->page, $query->itemsPerPage);
        }

        return $assetRepository->collection();
    }
}
