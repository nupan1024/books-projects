<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * Find all books with their average rating
     */
    public function findAllWithAverageRating(): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('b.id', 'b.title', 'b.author', 'b.published_year', 'AVG(r.rating) as average_rating')
            ->leftJoin('b.reviews', 'r')
            ->groupBy('b.id')
            ->getQuery()
            ->getResult();

        // Convert average_rating to proper format (null if no reviews, float otherwise)
        return array_map(function($book) {
            $book['average_rating'] = $book['average_rating'] ? round((float) $book['average_rating'], 1) : null;
            return $book;
        }, $result);
    }
}
