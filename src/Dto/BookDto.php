<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class BookDto
{
    #[Assert\NotBlank(groups: ['create', 'update'])]
    public ?string $title = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    public ?string $author = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Isbn(groups: ['create', 'update'])]
    public ?string $isbn = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Date(groups: ['create', 'update'])]
    public ?string $publicationDate = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    public ?string $genre = null;

    #[Assert\NotNull(groups: ['create', 'update'])]
    #[Assert\Type('integer', groups: ['create', 'update'])]
    #[Assert\PositiveOrZero(groups: ['create', 'update'])]
    public ?int $numberOfCopies = null;
}