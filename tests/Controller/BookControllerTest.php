<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Tests\ApiAuthWebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional tests for App\Controller\BookController
 *
 * In the test environment all /api/* routes are marked "security: false",
 * so the test client can call them without JWT headers.
 *
 * Before every test we recreate the database schema, guaranteeing that tests
 * stay isolated from each other.
 */
final class BookControllerTest extends ApiAuthWebTestCase
{

    /** Persist a simple Book entity directly via Doctrine. */
    private function persistBook(
        string $title = 'Temp',
        string $author = 'Author',
        string $isbn = '9780132350884'
    ): Book
    {
        $book = (new Book())
            ->setTitle($title)
            ->setAuthor($author)
            ->setIsbn($isbn)
            ->setPublicationDate(new \DateTimeImmutable('2024-01-01'))
            ->setGenre('Test')
            ->setNumberOfCopies(3);

        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }

    public function testCreateBookSuccessfully(): void
    {
        $payload = [
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn' => '978-0-13-235088-4',
            'publicationDate' => '2008-08-01',
            'genre' => 'Software Engineering',
            'numberOfCopies' => 10,
        ];

        $this->client->request(
            method: 'POST',
            uri: $this->uri('api_books.create'),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = $this->decodeResponseJson();
        $this->assertArrayHasKey('id', $data);
        $this->assertIsInt($data['id']);

        /** @var Book|null $book */
        $book = $this->em->getRepository(Book::class)->find($data['id']);
        $this->assertNotNull($book);

        $this->assertSame($payload['title'], $book->getTitle());
        $this->assertSame($payload['author'], $book->getAuthor());
        $this->assertSame($payload['isbn'], $book->getIsbn());
        $this->assertSame($payload['publicationDate'], $book->getPublicationDate()->format('Y-m-d'));
        $this->assertSame($payload['genre'], $book->getGenre());
        $this->assertSame($payload['numberOfCopies'], $book->getNumberOfCopies());
    }

    public function testCreateBookValidationErrors(): void
    {
        $this->client->request(
            'POST',
            $this->uri('api_books.create'),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey('errors', $this->decodeResponseJson());
    }

    public function testListBooksDefaultPagination(): void
    {
        foreach (range(1, 15) as $i) {
            $this->persistBook("T$i", "A$i", "9780132350" . str_pad($i, 3, '0', STR_PAD_LEFT));
        }

        $this->client->request('GET', $this->uri('api_books.list'));
        $this->assertResponseIsSuccessful();

        $data = $this->decodeResponseJson();

        $list = $data['items'] ?? $data;
        $this->assertCount(10, $list);
    }

    public function testUpdateBookSuccessfully(): void
    {
        $book = $this->persistBook();

        $payload = [
            'title' => 'Clean Coder',
            'author' => $book->getAuthor(),
            'isbn' => $book->getIsbn(),
            'publicationDate' => '2011-05-13',
            'genre' => 'Software Engineering',
            'numberOfCopies' => 20,
        ];

        $this->client->request(
            'PUT',
            $this->uri('api_books.update', ['id' => $book->getId()]),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $this->assertResponseIsSuccessful();

        $this->em->refresh($book);
        $this->assertSame('Clean Coder', $book->getTitle());
        $this->assertSame(20, $book->getNumberOfCopies());
        $this->assertSame('2011-05-13', $book->getPublicationDate()->format('Y-m-d'));
    }

    public function testUpdateBookNotFound(): void
    {
        $payload = [
            'title' => 'Whatever',
            'author' => 'X',
            'isbn' => '978-0-13-235088-4',
            'publicationDate' => '2024-01-01',
            'genre' => 'X',
            'numberOfCopies' => 1,
        ];

        $this->client->request(
            'PUT',
            $this->uri('api_books.update', ['id' => 999999]),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteBookSuccessfully(): void
    {
        $book = $this->persistBook();
        $id = $book->getId();

        $this->client->request('DELETE', $this->uri('api_books.delete', ['id' => $id]));
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $this->assertNull($this->em->find(Book::class, $id));
    }

}
