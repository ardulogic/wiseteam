<?php

namespace App\Controller\Api\Auth;

use App\Dto\UserDto;
use App\Factory\UserFactory;
use App\Validators\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(validationGroups: ['register'])] UserDto $dto,
        EntityManager $entityManager,
        UserFactory $userFactory,
    ): JsonResponse {
        $user = $userFactory->fromDto($dto);
        $entityManager->validateAndUpsert($user);

        return $this->json(['id' => $user->getId()], Response::HTTP_CREATED);
    }
}
