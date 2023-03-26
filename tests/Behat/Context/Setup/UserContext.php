<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Panda\Account\Domain\Factory\UserFactoryInterface;
use Panda\Account\Domain\Repository\UserRepositoryInterface;
use Panda\Tests\Behat\Context\Util\EnableClipboardTrait;

class UserContext implements Context
{
    use EnableClipboardTrait;

    public function __construct(
        private readonly UserFactoryInterface $userFactory,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly JWTEncoderInterface $jwtEncoder,
    ) {
    }

    /**
     * @Given istnieje użytkownik :email z hasłem :password
     * @Given istnieje użytkownik :email
     * @Given użytkownik o adresie email :email już istnieje
     */
    function there_is_a_user_with_email_and_password(string $email, string $password = 'I<3BambooShoots')
    {
        $user = $this->userFactory->create($email, $password);

        $this->userRepository->save($user);
        $this->entityManager->flush();
    }

    /**
     * @Given jestem zalogowany jako :email
     * @Given jestem zalogowany
     */
    function i_am_logged_in(string $email = 'panda@example.com')
    {
        $this->there_is_a_user_with_email_and_password($email);
        $user = $this->userRepository->findByEmail($email);

        $token = $this->jwtEncoder->encode([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);

        $this->clipboard->copy('authUser', $user);
        $this->clipboard->copy('token', $token);
    }

    /**
     * @Given znam swój identyfikator użytkownika
     */
    function blank()
    {
    }
}
