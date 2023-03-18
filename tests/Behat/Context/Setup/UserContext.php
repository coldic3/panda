<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use App\Account\Domain\Factory\UserFactoryInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class UserContext implements Context
{
    public ?string $token = null;

    public function __construct(
        private readonly UserFactoryInterface $userFactory,
        private readonly UserRepositoryInterface $userRepository,
        private readonly JWTEncoderInterface $jwtEncoder,
    ) {
    }

    /**
     * @Given istnieje użytkownik :email z hasłem :password
     * @Given istnieje użytkownik :email
     * @Given użytkownik o adresie email :email już istnieje
     */
    public function there_is_a_user_with_email_and_password(string $email, string $password = 'I<3BambooShoots'): void
    {
        $user = $this->userFactory->create($email, $password);

        $this->userRepository->save($user);
    }

    /**
     * @Given jestem zalogowany jako :email
     * @Given jestem zalogowany
     */
    public function i_am_logged_in(string $email = 'panda@example.com'): void
    {
        $this->there_is_a_user_with_email_and_password($email);
        $user = $this->userRepository->findByEmail($email);

        $this->token = $this->jwtEncoder->encode([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Given znam swój identyfikator użytkownika
     */
    public function blank(): void
    {
    }
}
