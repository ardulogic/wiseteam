<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank(groups: ['register'])]
    #[Assert\Length(max: 100, groups: ['register'])]
    public string $name;

    #[Assert\NotBlank(groups: ['register'])]
    #[Assert\Length(max: 100, groups: ['register'])]
    public string $surname;

    #[Assert\NotBlank(groups: ['register', 'login'])]
    #[Assert\Email(groups: ['register', 'login'])]
    public string $email;

    #[Assert\NotBlank(groups: ['register', 'login'])]
    #[Assert\Length(min: 6, groups: ['register'])] // No length check for login
    public string $password;
}
