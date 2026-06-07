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
            die("Borrow ID is missing.");
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
            die("Borrow ID is missing.");
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
            die("No valid file uploaded.");
        }

        $borrowId = (int)($_POST['borrow_id'] ?? 0);

        // ✅ Safe upload folder configuration
        $uploadDir = BASE_PATH . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Secure filename formatting
        $fileName = time() . '_' . basename($_FILES['proof']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['proof']['tmp_name'], $uploadPath)) {
            die("File upload operation failed.");
        }

        // ✅ UseCase implementation layer (Clean DDD architecture alignment)
        $useCase = new UploadPaymentProof($this->service);
        $useCase->execute($borrowId, $fileName);

        // Fetch application database engine instance
        $db = \App\DB\Database::getConnection();
        $notifModel = new \App\Notification\Repository\NotificationRepository($db);
        
        $currentUsername = $_SESSION['user']['username'];
        $currentUserId = (int)$_SESSION['user']['user_id'];
        $adminUserId = 1; // Admin account ID should be configured centrally if possible
        
        // One clear admin notification for proof submission
        $msgText = "User '{$currentUsername}' has submitted payment proof for Borrow ID #{$borrowId}.";
        $notifModel->createNotification($currentUserId, $adminUserId, $msgText);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
        }

        header('Location: ?page=borrow-detail&id=' . $borrowId);
        exit;
    }

    public function invoice()
    {
        $paymentId = (int)($_GET['payment_id'] ?? 0);

        if (!$paymentId) {
            die("Payment ID is missing.");
        }

        $payment = $this->service->findInvoiceData($paymentId);

        if (!$payment) {
            die("Requested invoice records could not be found.");
        }

        require BASE_PATH . '/App/Payment/Presentation/View/invoice.php';
    }
}