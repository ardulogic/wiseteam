<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[UniqueEntity(
    fields: ['isbn'],
    message: 'book.isbn.unique',
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $title = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 13, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Isbn]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $genre = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $numberOfCopies = null;

    // These are fields that mimic functional indexes (MySQL doesn't support functional indexes)
    // but we do have rules to populate them at database level
    #[ORM\Column(name: "title_lower", type: "string", nullable: true, insertable: false, updatable: false)]
    private ?string $titleLower = null;

    #[ORM\Column(name: "author_lower", type: "string", nullable: true, insertable: false, updatable: false)]
    private ?string $authorLower = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleLower(): ?string
    {
        return $this->titleLower;
    }

    public function getAuthorLower(): ?string
    {
        return $this->authorLower;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        if (!$this->isbn) {
            return null;
        }

        $formats = [
            13 => [3, 1, 2, 6, 1],  // ISBN-13
            10 => [1, 3, 5, 1],     // ISBN-10
        ];

        $length = strlen($this->isbn);

        if (!isset($formats[$length])) {
            return $this->isbn; // Unrecognized format
        }

        $parts = [];
        $offset = 0;

        foreach ($formats[$length] as $size) {
            $parts[] = substr($this->isbn, $offset, $size);
            $offset += $size;
        }

        return implode('-', $parts);
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = preg_replace('/[^0-9X]/', '', $isbn);

        return $this;
    }

    public function getPublicationDate(): \DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getNumberOfCopies(): ?int
    {
        return $this->numberOfCopies;
    }

    public function setNumberOfCopies(int $numberOfCopies): static
    {
        $this->numberOfCopies = $numberOfCopies;

        return $this;
    }
}
