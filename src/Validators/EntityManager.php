<?php

namespace App\Validators;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class EntityManager
{
    public function __construct(
        private ValidatorInterface     $validator,
        private EntityManagerInterface $em,
    )
    {
    }

    public function validate(object $entity): void
    {
        $violations = $this->validator->validate($entity);

        if ($violations->count() > 0) {
            // We do this, so we don't have to catch dto and entity errors separately
            // in the ValidationExceptionSubscriber

            // Same wrapper Symfony’s #[MapRequestPayload] uses
            throw new UnprocessableEntityHttpException(
                'Validation failed.',
                new ValidationFailedException($entity, $violations)
            );
        }
    }

    public function validateAndUpsert(object $entity): void
    {
        $this->validate($entity);

        if (!$this->em->contains($entity)) {
            $this->em->persist($entity);
        }
        $this->em->flush();
    }

    public function remove(Entity $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
