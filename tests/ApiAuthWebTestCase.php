<?php

namespace App\Tests;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Bootstraps a client and gives you a ready‑to‑use Bearer token.
 */
abstract class ApiAuthWebTestCase extends ApiWebTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->login();
    }

    /** Creates a throw‑away user, signs a JWT, and attaches it to the client. */
    protected function login(
        string $email = 'tester@example.com',
        string $password = 'T3stPa$$',
        string $role = 'ROLE_USER'
    ): User {
        // Create new user
        $user = (new User())
            ->setEmail($email)
            ->setName('Test')
            ->setSurname('User')
            ->setRoles([$role]);

        $hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        // Acquire token
        $jwtManager = self::getContainer()->get(JWTTokenManagerInterface::class);
        $token      = $jwtManager->create($user);

        // Set client header
        $this->client->setServerParameter('HTTP_Authorization', "Bearer $token");

        return $user;
    }

}