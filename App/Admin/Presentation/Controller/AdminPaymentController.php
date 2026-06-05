<?php

namespace App\Admin\Presentation\Controller;

use App\Payment\Infrastructure\Persistence\PaymentRepository;

class AdminPaymentController
{
    public function __construct(
        private PaymentRepository $paymentRepo
    ) {}

    public function index()
    {
        $payments = $this->paymentRepo->findAllWithBorrow();

        $pageTitle = "Admin Payments";
        $section = "admin-payments";

       require BASE_PATH . '/App/Admin/Presentation/view/admin-payments.php';
    }

    public function approve(int $paymentId)
{
    // Mark payment as paid
    $this->paymentRepo->approve($paymentId);

    // Redirect back to admin payments page
    header("Location: " . BASE_URL . "/Public/index.php?page=admin-payments");
    exit;
}
}