<?php
namespace App\Payment\Application\DTO;

class PaymentResponseDTO
{
    public int $paymentId;
    public int $borrowId;
    public float $amount;
    public string $status;
    public string $paymentDate;
}