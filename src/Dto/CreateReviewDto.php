<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewDto
{
    #[Assert\NotBlank(message: 'book_id is required')]
    #[Assert\Type(type: 'integer', message: 'book_id must be an integer')]
    #[Assert\Positive(message: 'book_id must be a positive integer')]
    public ?int $book_id = null;

    #[Assert\NotBlank(message: 'rating is required')]
    #[Assert\Type(type: 'integer', message: 'rating must be an integer')]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'rating must be an integer between {{ min }} and {{ max }}'
    )]
    public ?int $rating = null;

    #[Assert\NotBlank(message: 'comment is required')]
    #[Assert\Type(type: 'string', message: 'comment must be a string')]
    #[Assert\Length(
        min: 1,
        minMessage: 'comment cannot be empty'
    )]
    public ?string $comment = null;

    public function __construct(?int $book_id = null, ?int $rating = null, ?string $comment = null)
    {
        $this->book_id = $book_id;
        $this->rating = $rating;
        $this->comment = $comment ? trim($comment) : null;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['book_id'] ?? null,
            $data['rating'] ?? null,
            $data['comment'] ?? null
        );
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
