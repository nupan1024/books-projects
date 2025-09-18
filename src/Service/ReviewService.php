<?php

namespace App\Service;

use App\Entity\Review;
use App\Entity\Book;
use App\Repository\ReviewRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ReviewService
{
    public function __construct(
        private ReviewRepository $reviewRepository,
        private ValidatorInterface $validator
    ) {}

    /**
     * Get all reviews with pagination
     */
    public function getAllReviews(int $page = 1, int $limit = 10): array
    {
        $reviews = $this->reviewRepository->findWithPagination($page, $limit);
        $total = $this->reviewRepository->countTotal();
        $totalPages = ceil($total / $limit);

        return [
            'reviews' => $reviews,
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
     * Get a review by ID
     */
    public function getReviewById(int $id): ?Review
    {
        return $this->reviewRepository->find($id);
    }

    /**
     * Create a new review
     */
    public function createReview(Book $book, array $data): array
    {
        $review = new Review();
        $review->setBook($book);
        $this->mapDataToReview($review, $data);

        $violations = $this->validator->validate($review);
        
        if (count($violations) > 0) {
            return [
                'success' => false,
                'errors' => $this->formatValidationErrors($violations),
                'review' => null
            ];
        }

        $savedReview = $this->reviewRepository->save($review);

        return [
            'success' => true,
            'errors' => [],
            'review' => $savedReview
        ];
    }

    /**
     * Update an existing review
     */
    public function updateReview(Review $review, array $data): array
    {
        $this->mapDataToReview($review, $data);

        $violations = $this->validator->validate($review);
        
        if (count($violations) > 0) {
            return [
                'success' => false,
                'errors' => $this->formatValidationErrors($violations),
                'review' => null
            ];
        }

        $savedReview = $this->reviewRepository->save($review);

        return [
            'success' => true,
            'errors' => [],
            'review' => $savedReview
        ];
    }

    /**
     * Delete a review
     */
    public function deleteReview(Review $review): void
    {
        $this->reviewRepository->delete($review);
    }

    /**
     * Get reviews for a book
     */
    public function getReviewsForBook(Book $book): array
    {
        return $this->reviewRepository->findByBook($book);
    }

    /**
     * Get reviews by rating
     */
    public function getReviewsByRating(int $rating): array
    {
        return $this->reviewRepository->findByRating($rating);
    }

    /**
     * Get reviews by minimum rating
     */
    public function getReviewsByMinimumRating(int $minRating): array
    {
        return $this->reviewRepository->findByMinimumRating($minRating);
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews(int $limit = 10): array
    {
        return $this->reviewRepository->findRecentReviews($limit);
    }

    /**
     * Get average rating for a book
     */
    public function getAverageRatingForBook(Book $book): ?float
    {
        return $this->reviewRepository->getAverageRatingForBook($book);
    }

    /**
     * Count reviews for a book
     */
    public function countReviewsForBook(Book $book): int
    {
        return $this->reviewRepository->countReviewsForBook($book);
    }

    /**
     * Get rating distribution for a book
     */
    public function getRatingDistributionForBook(Book $book): array
    {
        return $this->reviewRepository->getRatingDistributionForBook($book);
    }

    /**
     * Get review statistics
     */
    public function getReviewStatistics(): array
    {
        $totalReviews = $this->reviewRepository->countTotal();
        $recentReviews = $this->reviewRepository->findRecentReviews(5);

        // Calculate rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = count($this->reviewRepository->findByRating($i));
        }

        // Calculate average rating
        $allReviews = $this->reviewRepository->findAll();
        $averageRating = null;
        if (!empty($allReviews)) {
            $totalRating = array_sum(array_map(fn($review) => $review->getRating(), $allReviews));
            $averageRating = round($totalRating / count($allReviews), 1);
        }

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
            'rating_distribution' => $ratingDistribution,
            'recent_reviews' => $recentReviews
        ];
    }

    /**
     * Map array data to Review entity
     */
    private function mapDataToReview(Review $review, array $data): void
    {
        if (isset($data['rating'])) {
            $review->setRating((int) $data['rating']);
        }

        if (isset($data['comment'])) {
            $review->setComment($data['comment']);
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
