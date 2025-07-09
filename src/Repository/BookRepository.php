<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchPaginated(string $q, int $page, int $perPage): array
    {
        $qb = $this->createQueryBuilder('b');

        $titleAuthorQuery = mb_strtolower($q);
        $isbnQuery = mb_strtolower(str_replace('-', '', $q));

        if ($q !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    // We're using optimised columns:
                    $qb->expr()->like('b.titleLower', ':qry'),
                    $qb->expr()->like('b.authorLower', ':qry'),
                    $qb->expr()->like('b.isbn', ':isbnQry')
                )
            )
                ->setParameter('qry', '%' . $titleAuthorQuery . '%')
                ->setParameter('isbnQry', '%' . $isbnQuery . '%');
        }

        $qb->orderBy('b.id', 'DESC');

        $qb->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($qb, true);

        return [iterator_to_array($paginator), $paginator->count()];
    }


    public function remove(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
