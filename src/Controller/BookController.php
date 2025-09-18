<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository
    ) {}

    /**
     * Get all books with average rating
     */
    #[Route('/api/books', name: 'api_books_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $books = $this->bookRepository->findAllWithAverageRating();

        return $this->json($books, Response::HTTP_OK);
    }
}
