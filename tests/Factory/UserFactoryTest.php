<?php

namespace App\Tests\Factory;

use App\Dto\UserDto;
use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @covers \App\Factory\UserFactory
 */
final class UserFactoryTest extends TestCase
{
    private static function makeDto(
        string $name     = 'Jonas',
        string $surname  = 'Valanciunas',
        string $email    = 'jonas@example.com',
        string $password = 'Secret123!'
    ): UserDto {
        $dto           = new UserDto();
        $dto->name     = $name;
        $dto->surname  = $surname;
        $dto->email    = $email;
        $dto->password = $password;

        return $dto;
    }

    public function testFromDtoCreatesPopulatedUserWithHashedPassword(): void
    {
        // Create a "fake password hasher"
        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher
            ->expects(self::once())
            ->method('hashPassword')
            ->with(self::isInstanceOf(User::class), 'Secret123!')
            ->willReturn('hashed‑pwd');

        $factory = new UserFactory($hasher);
        $dto     = self::makeDto(password: 'Secret123!');

        $user = $factory->fromDto($dto);

        self::assertInstanceOf(User::class, $user);
        self::assertSame('Jonas',            $user->getName());
        self::assertSame('Valanciunas',      $user->getSurname());
        self::assertSame('jonas@example.com',$user->getEmail());
        self::assertSame(['ROLE_USER'],      $user->getRoles());
        self::assertSame('hashed‑pwd',       $user->getPassword());
    }
}
