<?php

namespace App\Factory;

use App\Dto\BookDto;
use App\Entity\Book;

final class BookFactory
{
    public function fromDto(BookDto $dto): Book
    {
        return (new Book())
            ->setTitle($dto->title)
            ->setAuthor($dto->author)
            ->setIsbn($dto->isbn)
            ->setPublicationDate(new \DateTimeImmutable($dto->publicationDate))
            ->setGenre($dto->genre)
            ->setNumberOfCopies($dto->numberOfCopies);
    }

    public function updateFromDto(Book $book, BookDto $dto): Book
    {
        return $book
            ->setTitle($dto->title)
            ->setAuthor($dto->author)
            ->setIsbn($dto->isbn)
            ->setPublicationDate(new \DateTimeImmutable($dto->publicationDate))
            ->setGenre($dto->genre)
            ->setNumberOfCopies($dto->numberOfCopies);
    }

}
