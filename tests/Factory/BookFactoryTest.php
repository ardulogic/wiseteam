<?php

namespace App\Tests\Factory;

use App\Dto\BookDto;
use App\Entity\Book;
use App\Factory\BookFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Factory\BookFactory
 */
final class BookFactoryTest extends TestCase
{
    private BookFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new BookFactory();
    }

    /** Quickly create a DTO */
    private static function dto(
        string $title  = 'My book',
        string $author = 'Author',
        string $isbn   = '978-3-16-148410-0',
        string $date   = '2023-05-10',
        string $genre  = 'Fiction',
        int    $copies = 3,
    ): BookDto {
        $dto                  = new BookDto();
        $dto->title           = $title;
        $dto->author          = $author;
        $dto->isbn            = $isbn;
        $dto->publicationDate = $date;
        $dto->genre           = $genre;
        $dto->numberOfCopies  = $copies;

        return $dto;
    }

    /** Check entity values vs DTO */
    private static function assertBookEqualsDto(Book $book, BookDto $dto): void
    {
        self::assertSame($dto->title,           $book->getTitle());
        self::assertSame($dto->author,          $book->getAuthor());
        self::assertSame($dto->isbn,            $book->getIsbn());
        self::assertSame($dto->publicationDate, $book->getPublicationDate()->format('Y-m-d'));
        self::assertSame($dto->genre,           $book->getGenre());
        self::assertSame($dto->numberOfCopies,  $book->getNumberOfCopies());
    }

    public function testFromDtoCreatesNewEntity(): void
    {
        $dto  = self::dto();
        $book = $this->factory->fromDto($dto);

        self::assertInstanceOf(Book::class, $book);
        self::assertBookEqualsDto($book, $dto);
    }

    #[DataProvider('updateProvider')]
    public function testUpdateFromDtoMutatesExistingEntity(BookDto $updateDto): void
    {
        // start with an entity built from defaults
        $book = $this->factory->fromDto(self::dto());
        $this->factory->updateFromDto($book, $updateDto);

        self::assertBookEqualsDto($book, $updateDto);
    }

    public static function updateProvider(): \Generator
    {
        yield 'all fields updated' => [self::dto(
            title:  'Updated',
            author: 'Another Author',
            isbn:   '978-3-16-148410-1',
            date:   '2024-01-01',
            genre:  'Biography',
            copies: 10,
        )];
    }
}
