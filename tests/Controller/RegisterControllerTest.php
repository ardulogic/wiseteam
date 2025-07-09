<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\ApiWebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers App\Controller\Api\Auth\RegisterController
 */
final class RegisterControllerTest extends ApiWebTestCase
{
    private static function payload(
        string $email    = 'user@example.com',
        string $password = 'StrongPass123!',
        string $name     = 'Alice',
        string $surname  = 'Bomzara',
    ): array {
        return [
            'email'    => $email,
            'password' => $password,
            'name'     => $name,
            'surname'     => $surname,
        ];
    }

    public function testRegisterSuccessfully(): void
    {
        $payload = self::payload();

        $this->client->request(
            method:  'POST',
            uri:     $this->uri('api_register'),
            server:  ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR)
        );

        /* HTTP layer */
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $data = $this->decodeResponseJson();

        $this->assertArrayHasKey('id', $data);
        $this->assertIsInt($data['id']);

        /* Database layer */
        /** @var User|null $user */
        $user = $this->em->find(User::class, $data['id']);
        $this->assertNotNull($user);
        $this->assertSame($payload['email'], $user->getEmail());
        $this->assertSame($payload['name'],  $user->getName() ?? $user->getUsername());

        // cannot assert raw password (it’s hashed), but at least:
        $this->assertTrue(strlen($user->getPassword()) > 20);
    }

    public function testRegisterValidationErrors(): void
    {
        $this->client->request(
            'POST',
            uri:     $this->uri('api_register'),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $errors = $this->decodeResponseJson();
        $this->assertArrayHasKey('errors', $errors);
    }
}
