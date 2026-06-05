<?php

namespace App\Payment\Presentation\Controller;

use App\Payment\Application\Service\PaymentService;
use App\Payment\Application\DTO\PaymentRequestDTO;
use App\Payment\Application\UseCase\UploadPaymentProof;

class PaymentController
{
    private PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function store()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ?page=login');
            exit;
        }

        $dto = new PaymentRequestDTO();

        $dto->borrowId = (int)($_POST['borrow_id'] ?? 0);
        $dto->userId   = (int)$_SESSION['user']['user_id'];
        $dto->amount   = (float)($_POST['amount'] ?? 0);

        $this->service->create($dto);

        header('Location: ?page=payment-success');
        exit;
    }

    public function process()
    {
        $borrowId = $_GET['borrow_id'] ?? null;

        if (!$borrowId) {
            die("Borrow ID missing");
        }

        $payment = $this->service->findByBorrowIdWithTitle((int)$borrowId);

        if (!$payment) {
            $payment = [
                'borrow_id' => $borrowId,
                'title' => 'Unknown',
                'amount' => 5.00,
                'status' => 'unpaid',
                'payment_date' => null
            ];
        }

        require BASE_PATH . '/App/Payment/Presentation/View/pay-process.php';
    }

    public function show()
    {
        $borrowId = $_GET['borrow_id'] ?? null;

        if (!$borrowId) {
            die("Borrow ID missing");
        }

        $payment = $this->service->findByBorrowIdWithTitle((int)$borrowId);

        if (!$payment) {
            $payment = [
                'title' => 'Unknown',
                'borrow_id' => $borrowId,
                'amount' => 5.00,
                'status' => 'unpaid',
                'payment_date' => null
            ];
        }

        $payment['status'] = strtolower(trim((string)($payment['status'] ?? 'unpaid')));
        $payment['amount'] = (float)($payment['amount'] ?? 5.00);
        $payment['borrow_id'] = (int)$borrowId;

        require BASE_PATH . '/App/Payment/Presentation/View/payment.php';
    }

    public function history()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ?page=login');
            exit;
        }

        $payments = $this->service->getUserPayments(
            (int)$_SESSION['user']['user_id']
        );

        require BASE_PATH . '/App/Payment/Presentation/View/payment-history.php';
    }

    public function uploadProof()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ?page=login');
            exit;
        }

        if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== UPLOAD_ERR_OK) {
            die("No valid file uploaded");
        }

        $borrowId = (int)($_POST['borrow_id'] ?? 0);

        // ✅ safe upload folder
        $uploadDir = BASE_PATH . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            }

            // secure filename
            $fileName = time() . '_' . basename($_FILES['proof']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['proof']['tmp_name'], $uploadPath)) {
                die("Upload failed");
            }

            // ✅ UseCase layer (clean DDD)
            $useCase = new UploadPaymentProof($this->service);
            $useCase->execute($borrowId, $fileName);

        header('Location: ?page=borrow-detail&id=' . urlencode($borrowId));
        exit;
        }

 public function invoice()
{
    $paymentId = (int)($_GET['payment_id'] ?? 0);

    if (!$paymentId) {
        die("Payment ID missing");
    }

    $payment = $this->service->findInvoiceData($paymentId);

    if (!$payment) {
        die("Invoice not found");
    }

    require BASE_PATH . '/App/Payment/Presentation/View/invoice.php';
}
}