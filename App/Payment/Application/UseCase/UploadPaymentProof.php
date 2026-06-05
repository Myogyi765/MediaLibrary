<?php

namespace App\Payment\Application\UseCase;

use App\Payment\Application\Service\PaymentService;

class UploadPaymentProof
{
    public function __construct(
        private PaymentService $service
    ) {}

    public function execute(int $borrowId, string $fileName): void
    {
        if ($borrowId <= 0 || $fileName === '') {
            throw new \InvalidArgumentException("Invalid upload data");
        }

        // business rule: upload proof → set status pending
        $this->service->uploadProof($borrowId, $fileName);
    }
}