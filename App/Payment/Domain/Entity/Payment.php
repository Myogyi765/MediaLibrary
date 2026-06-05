<?php

namespace App\Payment\Domain\Entity;

class Payment
{
    private ?int $paymentId;
    private int $borrowId;
    private int $userId;
    private float $amount;
    private string $status;
    private string $paymentDate;

    public function __construct(
        ?int $paymentId,
        int $borrowId,
        int $userId,
        float $amount,
        string $status,
        string $paymentDate
    ) {
        $this->paymentId = $paymentId;
        $this->borrowId = $borrowId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->status = $status;
        $this->paymentDate = $paymentDate;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function getBorrowId(): int
    {
        return $this->borrowId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPaymentDate(): string
    {
        return $this->paymentDate;
    }
} 