<?php

namespace App\Borrow\Domain\Service;

use App\Borrow\Domain\Repository\BorrowRepositoryInterface;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;

class BorrowService
{
    public function __construct(
        private BorrowRepositoryInterface $repo,
        private PaymentRepositoryInterface $paymentRepo
    ) {}

    public function borrowBook(int $userId, int $mediaId): void
    {
        $existing = $this->repo->findByUserAndMedia($userId, $mediaId);

        if ($existing) {
            if (in_array($existing['status'], ['pending', 'approved'])) {
                return;
            }
        }

        $this->repo->create([
            'user_id'     => $userId,
            'media_id'    => $mediaId,
            'borrow_date' => date('Y-m-d H:i:s'),
            'status'      => 'pending'
        ]);
    }

    /**
     * Approve borrow + create payment
     */
    public function approveBorrow(int $borrowId, int $userId): void
    {
        // 1. approve borrow
        $this->repo->update($borrowId, [
            'status' => 'approved'
        ]);

        // 2. create payment automatically
        $this->paymentRepo->create([
            'borrow_id'    => $borrowId,
            'user_id'      => $userId,
            'amount'       => 5.00,
            'status'       => 'unpaid',
            'payment_date' => null
        ]);
    }

    public function returnBook(int $borrowId): void
    {
        $this->repo->update($borrowId, [
            'status'      => 'returned',
            'return_date' => date('Y-m-d H:i:s')
        ]);
     

    }

    public function getUserBorrows(int $userId)
    {
        return $this->repo->findByUserId($userId);
    }
}