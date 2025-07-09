<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\ApiWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Functional tests for the JWT login endpoint (e.g. /api/login).
 */
final class LoginControllerTest extends ApiWebTestCase
{

    private function createUser(
        string $email = 'user@example.com',
        string $password = 'StrongPass123!',
        string $name = 'Jonas',
        string $surname = 'Valanciunas'
    ): User
    {
        $user = (new User())
            ->setEmail($email)
            ->setName($name)           // 👈 non‑nullable column
            ->setSurname($surname)     // 👈 non‑nullable column
            ->setRoles(['ROLE_USER']); // if roles is required

        /** @var UserPasswordHasherInterface $hasher */
        $hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function testLoginReturnsJwtToken(): void
    {
        $email = 'login@example.com';
        $password = 'P@ssw0rd!';
        $this->createUser($email, $password);

        $this->client->request(
            method: 'POST',
            uri: '/api/login',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                'email' => $email,
                'password' => $password,
            ], JSON_THROW_ON_ERROR)
        );

        // HTTP 200
        $this->assertResponseIsSuccessful();
        $data = $this->decodeResponseJson();

        // Lexik returns {"token": "..."}
        $this->assertArrayHasKey('token', $data);
        $this->assertIsString($data['token']);
        $this->assertGreaterThan(40, strlen($data['token'])); // basic sanity check
    }

    public function testLoginWithBadPasswordFails(): void
    {
        $email = 'badpw@example.com';
        $password = 'CorrectPassword!';
        $this->createUser($email, $password);

        $this->client->request(
            method: 'POST',
            uri: '/api/login',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                'email' => $email,
                'password' => 'BadpasswordZZ',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $data = $this->decodeResponseJson();
        $this->assertArrayHasKey('message', $data);
        // e.g. {"code":401,"message":"Invalid credentials."}
    }
}
