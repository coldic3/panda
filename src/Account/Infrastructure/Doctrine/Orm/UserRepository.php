<?php

namespace Panda\Account\Infrastructure\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Panda\Account\Domain\Model\User;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Shared\Infrastructure\Doctrine\Orm\DoctrineRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;

class UserRepository extends DoctrineRepository implements UserRepositoryInterface
{
    private const ENTITY_CLASS = User::class;
    private const ALIAS = 'user';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(UserInterface $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(UserInterface $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function findById(Uuid $id): ?UserInterface
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->em->getRepository(self::ENTITY_CLASS)->findOneBy(['email' => $email]);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user);
    }
}
