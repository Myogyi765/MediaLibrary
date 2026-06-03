<?php

namespace App\Borrow\Domain\Service;

use App\Borrow\Domain\Repository\BorrowRepositoryInterface;

class BorrowService
{
    public function __construct(
        private BorrowRepositoryInterface $repo
    ) {}

    public function borrowBook(int $userId, int $mediaId): void
    {
        $this->repo->create([
            'user_id' => $userId,
            'media_id' => $mediaId,
            'borrow_date' => date('Y-m-d'),
            'status' => 'pending'
        ]);
    }

    public function returnBook(int $borrowId): void
    {
        $this->repo->update($borrowId, [
            'status' => 'returned',
            'return_date' => date('Y-m-d')
        ]);
    }

    public function getUserBorrows(int $userId)
    {
        return $this->repo->findByUserId($userId);
    }
}