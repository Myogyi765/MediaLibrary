<?php
namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Entity\Payment;

interface PaymentRepositoryInterface
{

public function updateProof(int $borrowId, string $fileName): void;
public function findByBorrowIdWithTitle(int $borrowId): ?array;
    public function save(Payment $payment): bool;

    public function findById(int $id): ?Payment;

    public function findByUser(int $userId): array;
       public function create(array $data): void;

    public function findAll(): array;
    public function findByBorrowId(int $borrowId);
    public function findAllWithBorrow(): array;

    public function findByIdWithDetails(int $paymentId): ?array;
}