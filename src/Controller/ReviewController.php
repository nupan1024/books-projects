<?php

namespace App\Controller;

use App\Dto\CreateReviewDto;
use App\Entity\Review;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository,
        private ReviewRepository $reviewRepository,
        private ValidatorInterface $validator
    ) {}

    /**
     * Create a new review
     */
    #[Route('/api/reviews', name: 'api_reviews_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'error' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Create and validate DTO
        $dto = CreateReviewDto::fromArray($data);
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->json([
                'error' => 'Validation failed',
                'details' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if book exists
        $book = $this->bookRepository->find($dto->getBookId());
        if (!$book) {
            return $this->json([
                'error' => 'Book not found'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Create and save review
        $review = new Review();
        $review->setBook($book);
        $review->setRating($dto->getRating());
        $review->setComment($dto->getComment());

        $savedReview = $this->reviewRepository->save($review);

        return $this->json([
            'id' => $savedReview->getId(),
            'created_at' => $savedReview->getCreatedAt()->format('Y-m-d H:i:s')
        ], Response::HTTP_CREATED);
    }
}
