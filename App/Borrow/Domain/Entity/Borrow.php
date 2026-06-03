<?php

namespace App\Borrow\Domain\Entity;

class Borrow
{
    private ?int $id;
    private int $userId;
    private int $mediaId;
    private string $borrowDate;
    private ?string $returnDate;
    private string $status;

    public function __construct(
        int $userId,
        int $mediaId,
        string $borrowDate,
        string $status = 'borrowed',
        ?string $returnDate = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->mediaId = $mediaId;
        $this->borrowDate = $borrowDate;
        $this->returnDate = $returnDate;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getMediaId(): int { return $this->mediaId; }
    public function getBorrowDate(): string { return $this->borrowDate; }
    public function getReturnDate(): ?string { return $this->returnDate; }
    public function getStatus(): string { return $this->status; }

    public static function fromArray(array $data): self
    {
        return new self(
            (int)$data['user_id'],
            (int)$data['media_id'],
            $data['borrow_date'],
            $data['status'] ?? 'borrowed',
            $data['return_date'] ?? null,
            isset($data['borrow_id']) ? (int)$data['borrow_id'] : null
        );
    }
}