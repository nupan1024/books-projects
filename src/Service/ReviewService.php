<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class ReviewService
{
    public function __construct(
        private ReviewRepository $reviewRepository
    ) {}
}
