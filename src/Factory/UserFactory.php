<?php

namespace App\Factory;

use App\Dto\UserDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function fromDto(UserDto $dto): User
    {
        $user = new User();
        $user->setName($dto->name);
        $user->setSurname($dto->surname);
        $user->setEmail($dto->email);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);

        return $user;
    }
}
