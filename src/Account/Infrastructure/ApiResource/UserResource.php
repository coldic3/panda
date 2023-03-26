<?php

declare(strict_types=1);

namespace Panda\Account\Infrastructure\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Panda\Account\Domain\Model\UserInterface;
use Panda\Account\Infrastructure\ApiState\Processor\UserCreateProcessor;
use Panda\Account\Infrastructure\ApiState\Provider\UserProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Get(provider: UserProvider::class),
        new Post(
            openapiContext: [
                'responses' => [
                    '204' => ['description' => 'User created or already exists'],
                    '400' => ['description' => 'Invalid input'],
                    '422' => ['description' => 'Unprocessable entity'],
                ],
            ],
            validationContext: ['groups' => self::WRITABLE_GROUPS],
            output: false,
            processor: UserCreateProcessor::class,
        ),
    ],
    denormalizationContext: ['groups' => self::WRITABLE_GROUPS],
)]
final class UserResource
{
    private const WRITABLE_GROUPS = ['create'];

    public function __construct(
        #[ApiProperty(readable: false, writable: false, identifier: true)]
        public ?Uuid $id = null,

        #[ApiProperty(default: 'panda@example.com')]
        #[Assert\NotBlank(groups: self::WRITABLE_GROUPS)]
        #[Assert\Length(max: 180, groups: self::WRITABLE_GROUPS)]
        #[Assert\Email(groups: self::WRITABLE_GROUPS)]
        #[Groups(self::WRITABLE_GROUPS)]
        public ?string $email = null,

        #[ApiProperty(readable: false, default: 'password')]
        #[Assert\NotBlank(groups: self::WRITABLE_GROUPS)]
        #[Assert\Length(min: 8, groups: self::WRITABLE_GROUPS)]
        #[Groups(self::WRITABLE_GROUPS)]
        public ?string $password = null,
    ) {
    }

    public static function fromModel(UserInterface $user): UserResource
    {
        return new self($user->getId(), $user->getEmail(), $user->getPassword());
    }
}
