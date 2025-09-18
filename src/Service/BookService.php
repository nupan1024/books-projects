<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository
    ) {}

    /**
     * Get a book by ID
     */
    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }
}
