<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = $this->loadBooks($manager);
        $manager->flush();
        
        $this->loadReviews($manager, $books);
        $manager->flush();
    }

    private function loadBooks(ObjectManager $manager): array
    {
        $booksData = [
            [
                'title' => 'El Arte de Programar',
                'author' => 'Donald Knuth',
                'published_year' => 1968
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'published_year' => 2008
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'published_year' => 1999
            ]
        ];

        $books = [];
        foreach ($booksData as $bookData) {
            $book = new Book();
            $book->setTitle($bookData['title']);
            $book->setAuthor($bookData['author']);
            $book->setPublishedYear($bookData['published_year']);

            $manager->persist($book);
            $books[] = $book;
        }

        return $books;
    }

    private function loadReviews(ObjectManager $manager, array $books): void
    {
        $reviewsData = [
            // Reviews for "El Arte de Programar" (index 0) - 2 reviews
            [
                'book_index' => 0,
                'rating' => 5,
                'comment' => 'Una obra magistral que sentó las bases de la programación moderna. Knuth demuestra una comprensión profunda de los algoritmos y estructuras de datos. Indispensable para cualquier programador serio.'
            ],
            [
                'book_index' => 0,
                'rating' => 4,
                'comment' => 'Excelente contenido técnico, aunque puede resultar denso para principiantes. La rigurosidad matemática es impresionante, pero requiere dedicación para aprovecharlo al máximo.'
            ],

            // Reviews for "Clean Code" (index 1) - 2 reviews
            [
                'book_index' => 1,
                'rating' => 5,
                'comment' => 'Revolucionó mi forma de escribir código. Martin presenta principios claros y prácticos que todo desarrollador debería conocer. Las técnicas de refactoring son invaluables.'
            ],
            [
                'book_index' => 1,
                'rating' => 2,
                'comment' => 'Aunque tiene buenos conceptos, algunos ejemplos parecen forzados y dogmáticos. No todos los principios se aplican bien en todos los contextos de desarrollo.'
            ],

            // Reviews for "Refactoring" (index 2) - 2 reviews
            [
                'book_index' => 2,
                'rating' => 4,
                'comment' => 'Guía práctica y detallada sobre cómo mejorar código existente. Fowler explica cada técnica con ejemplos claros. Muy útil para mantener código legible y mantenible.'
            ],
            [
                'book_index' => 2,
                'rating' => 1,
                'comment' => 'Demasiado enfocado en ejemplos específicos que se sienten obsoletos. Los conceptos son válidos pero la presentación podría ser más moderna y aplicable a tecnologías actuales.'
            ]
        ];

        foreach ($reviewsData as $reviewData) {
            $review = new Review();
            $review->setBook($books[$reviewData['book_index']]);
            $review->setRating($reviewData['rating']);
            $review->setComment($reviewData['comment']);

            $manager->persist($review);
        }
    }
}
