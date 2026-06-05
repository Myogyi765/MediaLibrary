<?php

namespace App\Payment\Application\DTO;

class PaymentRequestDTO
{
    public int $borrowId;
    public int $userId;
    public float $amount;
}