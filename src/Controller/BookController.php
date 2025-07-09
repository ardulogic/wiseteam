<?php

namespace App\Controller;

use App\Dto\BookDto;
use App\Factory\BookFactory;
use App\Repository\BookRepository;
use App\Validators\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * BookController
 *
 * Note: We collect all validation errors with ValidationExceptionSubscriber
 * and then output a nice array of them for frontend
 *
 * There is DTO validation and Entity validation
 * DTO handles the incoming data from POST which are strings
 * Entity validation handles uniqueness/database related stuff
 * We could use only Entity validation, but for instance date comes in as string,
 * but entity has a Date object. We could hack it, but this is cleaner, more robust two stage validation
 */
#[Route('/api/books', name: 'api_books.')]
final class BookController extends AbstractController
{

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(validationGroups: ['create'])] BookDto $dto,
        EntityManager                                              $entityManager,
        BookFactory                                                $factory,
    ): JsonResponse
    {
        $book = $factory->fromDto($dto);
        $entityManager->validateAndUpsert($book); // Handle entity validation

        return $this->json(['id' => $book->getId()], Response::HTTP_CREATED);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        Request             $request,
        BookRepository      $repository,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = max(1, min(100, (int)$request->query->get('perPage', 10)));
        $q = trim((string)$request->query->get('q', ''));

        [$books, $total] = $repository->searchPaginated($q, $page, $perPage);

        $data = [
            'items' => json_decode(
                $serializer->serialize($books, 'json'),
                true
            ),
            'meta' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => (int)ceil($total / $perPage),
            ],
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int                                                        $id,
        #[MapRequestPayload(validationGroups: ['update'])] BookDto $dto,
        BookRepository                                             $repository,
        BookFactory                                                $factory,
        EntityManager                                              $entityManager,
    ): JsonResponse
    {
        $book = $repository->find($id);

        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $factory->updateFromDto($book, $dto);
        $entityManager->validateAndUpsert($book);

        return $this->json(['id' => $book->getId()], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int            $id,
        BOokRepository $repository,
    ): JsonResponse
    {
        $book = $repository->find($id);

        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $repository->remove($book);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}
