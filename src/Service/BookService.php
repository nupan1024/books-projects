<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private ValidatorInterface $validator
    ) {}

    /**
     * Get all books with pagination
     */
    public function getAllBooks(int $page = 1, int $limit = 10): array
    {
        $books = $this->bookRepository->findWithPagination($page, $limit);
        $total = $this->bookRepository->countTotal();
        $totalPages = ceil($total / $limit);

        return [
            'books' => $books,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'items_per_page' => $limit,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }

    /**
     * Get a book by ID
     */
    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    /**
     * Create a new book
     */
    public function createBook(array $data): array
    {
        $book = new Book();
        $this->mapDataToBook($book, $data);

        $violations = $this->validator->validate($book);
        
        if (count($violations) > 0) {
            return [
                'success' => false,
                'errors' => $this->formatValidationErrors($violations),
                'book' => null
            ];
        }

        $savedBook = $this->bookRepository->save($book);

        return [
            'success' => true,
            'errors' => [],
            'book' => $savedBook
        ];
    }

    /**
     * Update an existing book
     */
    public function updateBook(Book $book, array $data): array
    {
        $this->mapDataToBook($book, $data);

        $violations = $this->validator->validate($book);
        
        if (count($violations) > 0) {
            return [
                'success' => false,
                'errors' => $this->formatValidationErrors($violations),
                'book' => null
            ];
        }

        $savedBook = $this->bookRepository->save($book);

        return [
            'success' => true,
            'errors' => [],
            'book' => $savedBook
        ];
    }

    /**
     * Delete a book
     */
    public function deleteBook(Book $book): void
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Search books by criteria
     */
    public function searchBooks(array $criteria): array
    {
        return $this->bookRepository->searchBooks($criteria);
    }

    /**
     * Get books by title
     */
    public function getBooksByTitle(string $title): array
    {
        return $this->bookRepository->findByTitle($title);
    }

    /**
     * Get books by author
     */
    public function getBooksByAuthor(string $author): array
    {
        return $this->bookRepository->findByAuthor($author);
    }

    /**
     * Get books by genre
     */
    public function getBooksByGenre(string $genre): array
    {
        return $this->bookRepository->findByGenre($genre);
    }

    /**
     * Get books by year range
     */
    public function getBooksByYearRange(int $startYear, int $endYear): array
    {
        return $this->bookRepository->findByYearRange($startYear, $endYear);
    }

    /**
     * Get books in stock
     */
    public function getBooksInStock(): array
    {
        return $this->bookRepository->findBooksInStock();
    }

    /**
     * Get books by price range
     */
    public function getBooksByPriceRange(?float $minPrice = null, ?float $maxPrice = null): array
    {
        return $this->bookRepository->findByPriceRange($minPrice, $maxPrice);
    }

    /**
     * Get available genres
     */
    public function getAvailableGenres(): array
    {
        return $this->bookRepository->getAvailableGenres();
    }

    /**
     * Get recently added books
     */
    public function getRecentBooks(int $limit = 5): array
    {
        return $this->bookRepository->findRecentBooks($limit);
    }

    /**
     * Check if book is in stock
     */
    public function isBookInStock(Book $book): bool
    {
        return $book->getStock() > 0;
    }

    /**
     * Update book stock
     */
    public function updateStock(Book $book, int $quantity): array
    {
        if ($book->getStock() + $quantity < 0) {
            return [
                'success' => false,
                'message' => 'Insufficient stock available'
            ];
        }

        $book->setStock($book->getStock() + $quantity);
        $this->bookRepository->save($book);

        return [
            'success' => true,
            'message' => 'Stock updated successfully',
            'new_stock' => $book->getStock()
        ];
    }

    /**
     * Get book statistics
     */
    public function getBookStatistics(): array
    {
        $totalBooks = $this->bookRepository->countTotal();
        $booksInStock = count($this->bookRepository->findBooksInStock());
        $genres = $this->bookRepository->getAvailableGenres();

        return [
            'total_books' => $totalBooks,
            'books_in_stock' => $booksInStock,
            'out_of_stock' => $totalBooks - $booksInStock,
            'total_genres' => count($genres),
            'available_genres' => $genres
        ];
    }

    /**
     * Map array data to Book entity
     */
    private function mapDataToBook(Book $book, array $data): void
    {
        if (isset($data['title'])) {
            $book->setTitle($data['title']);
        }

        if (isset($data['author'])) {
            $book->setAuthor($data['author']);
        }

        if (isset($data['published_year'])) {
            $book->setPublishedYear((int) $data['published_year']);
        }

        if (isset($data['isbn'])) {
            $book->setIsbn($data['isbn']);
        }

        if (isset($data['description'])) {
            $book->setDescription($data['description']);
        }

        if (isset($data['genre'])) {
            $book->setGenre($data['genre']);
        }

        if (isset($data['price'])) {
            $book->setPrice($data['price']);
        }

        if (isset($data['stock'])) {
            $book->setStock((int) $data['stock']);
        }
    }

    /**
     * Format validation errors
     */
    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
