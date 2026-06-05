<?php
namespace App\Payment\Application\Mapper;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Application\DTO\PaymentResponseDTO;

class PaymentMapper
{
    public static function toDTO(Payment $payment): PaymentResponseDTO
    {
        $dto = new PaymentResponseDTO();

        $dto->paymentId = $payment->getPaymentId();
        $dto->borrowId = $payment->getBorrowId();
        $dto->amount = $payment->getAmount();
        $dto->status = $payment->getStatus();
        $dto->paymentDate = $payment->getPaymentDate();

        return $dto;
    }
}