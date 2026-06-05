<?php

namespace App\Payment\Application\Service;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Payment\Application\DTO\PaymentRequestDTO;

class PaymentService
{
    private PaymentRepositoryInterface $repository;

    public function __construct(
        PaymentRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

public function uploadProof(int $borrowId, string $fileName): void
{
    $this->repository->updateProof($borrowId, $fileName);
}

    public function create(
        PaymentRequestDTO $dto
    ): bool {
        $payment = new Payment(
            null,
            $dto->borrowId,
            $dto->userId,
            $dto->amount,
            'pending',
            date('Y-m-d H:i:s')
        );

        return $this->repository->save($payment);
    }
public function findByBorrowIdWithTitle(int $borrowId)
{
    return $this->repository->findByBorrowIdWithTitle($borrowId);
}

    public function getUserPayments(
        int $userId
    ): array {
        return $this->repository->findByUser($userId);
    }

    public function findByBorrowId(int $borrowId)
{
    return $this->repository->findByBorrowId($borrowId);
}


public function getAllPaymentsWithBorrow(): array
{
    return $this->repository->findAllWithBorrow();
}

public function findInvoiceData(int $paymentId): array
{
    return $this->repository->findByIdWithDetails($paymentId); // or whatever method fetches invoice info
}
}