<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrmContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /** @BeforeScenario */
    public function purgeDatabase(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
        $this->entityManager->clear();
    }
}
