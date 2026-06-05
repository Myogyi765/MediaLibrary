<?php

namespace App\Payment\Infrastructure\Persistence;

use PDO;
use Exception;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Payment\Domain\Entity\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get all payments with borrow info
     */
    public function findAllWithBorrow(): array
    {
        $stmt = $this->db->prepare("
            SELECT
                p.payment_id,
                p.borrow_id,
                p.user_id,
                u.username,
                p.amount,
                p.status,
                p.payment_date,
                p.proof_image,
                b.status AS borrow_status,
                m.title AS book_title
            FROM payments p
            JOIN borrows b ON p.borrow_id = b.borrow_id
            JOIN media m ON b.media_id = m.media_id
            JOIN users u ON p.user_id = u.user_id
            ORDER BY p.payment_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create payment (array version) - FIXED: Included user_id mapping
     */
    public function create(array $data): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments (borrow_id, user_id, amount, status, payment_date, proof_image)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['borrow_id'],
            $data['user_id'] ?? null,
            $data['amount'] ?? 5.00,
            $data['status'] ?? 'unpaid',
            $data['payment_date'] ?? date('Y-m-d H:i:s'),
            $data['proof_image'] ?? null
        ]);
    }

    /**
     * Save payment (Entity version)
     */
    public function save(Payment $payment): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments (borrow_id, user_id, amount, status, payment_date)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $payment->getBorrowId(),
            $payment->getUserId(),
            $payment->getAmount(),
            $payment->getStatus(),
            $payment->getPaymentDate()
        ]);
    }

    /**
     * Find payment by ID
     */
    public function findById(int $id): ?Payment
    {
        $stmt = $this->db->prepare("
            SELECT * FROM payments WHERE payment_id = ? LIMIT 1
        ");

        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Payment(
            (int)$data['payment_id'],
            (int)$data['borrow_id'],
            (int)$data['user_id'],
            (float)$data['amount'],
            $data['status'],
            $data['payment_date']
        );
    }

    /**
     * Find payment by borrow ID
     */
    public function findByBorrowId(int $borrowId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM payments WHERE borrow_id = ? LIMIT 1
        ");

        $stmt->execute([$borrowId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    /**
     * Find payments by user
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM payments WHERE user_id = ? ORDER BY payment_id DESC
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all payments
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM payments ORDER BY payment_id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment status
     */
    public function updateStatus(int $paymentId, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE payments SET status = :status WHERE payment_id = :id
        ");

        return $stmt->execute([
            'status' => $status,
            'id'     => $paymentId
        ]);
    }

    /**
     * Delete payment
     */
    public function delete(int $paymentId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM payments WHERE payment_id = ?
        ");

        return $stmt->execute([$paymentId]);
    }

    public function findByBorrowIdWithTitle(int $borrowId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                p.payment_id,
                COALESCE(p.borrow_id, b.borrow_id) AS borrow_id,
                COALESCE(p.user_id, b.user_id) AS user_id,
                COALESCE(p.amount, 5.00) AS amount,
                COALESCE(p.status, 'unpaid') AS status,
                p.payment_date,
                m.title
            FROM borrows b
            JOIN media m ON b.media_id = m.media_id
            LEFT JOIN payments p ON p.borrow_id = b.borrow_id
            WHERE b.borrow_id = ?
            LIMIT 1
        ");

        $stmt->execute([$borrowId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function updateProof(int $borrowId, string $fileName): void
    {
        $stmt = $this->db->prepare("SELECT user_id FROM borrows WHERE borrow_id = ?");
        $stmt->execute([$borrowId]);
        $borrow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$borrow) {
            throw new Exception("Borrow context record not found");
        }

        $userId = (int)$borrow['user_id'];

        $check = $this->db->prepare("SELECT payment_id FROM payments WHERE borrow_id = ?");
        $check->execute([$borrowId]);
        $payment = $check->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            $update = $this->db->prepare("
                UPDATE payments SET proof_image = ?, status = 'unpaid' WHERE borrow_id = ?
            ");
            $update->execute([$fileName, $borrowId]);
            return;
        }

        $insert = $this->db->prepare("
            INSERT INTO payments (borrow_id, user_id, amount, status, payment_date, proof_image)
            VALUES (?, ?, 5.00, 'unpaid', NOW(), ?)
        ");
        $insert->execute([$borrowId, $userId, $fileName]);
    }

    /**
     * Approve Payment & update Borrow status safely - FIXED: Subquery lock avoided
     */
    public function approve(int $paymentId): void
    {
        $stmt = $this->db->prepare("
            SELECT user_id, borrow_id, amount FROM payments WHERE payment_id = ? LIMIT 1
        ");
        $stmt->execute([$paymentId]);
        $paymentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paymentDetails) {
            throw new Exception("Payment item verification failed.");
        }

        $userId = (int)$paymentDetails['user_id'];
        $borrowId = (int)$paymentDetails['borrow_id'];
        $amountValue = (float)($paymentDetails['amount'] ?? 5.00);

        // Run isolated modifications to prevent Error 1093
        $this->db->prepare("
            UPDATE payments SET status='paid', approved_at=NOW() WHERE payment_id=?
        ")->execute([$paymentId]);

        $this->db->prepare("
            UPDATE borrows SET status='borrowed' WHERE borrow_id=?
        ")->execute([$borrowId]);

        // Trigger Notification Model Engine payload injection safely
        $notificationPath = BASE_PATH . '/App/Notification/Model/NotificationModel.php';
        if (file_exists($notificationPath)) {
            require_once $notificationPath;
            $notificationModel = new \NotificationModel($this->db);

            $notificationModel->create(
                $userId,
                "Payment Approved 🎉",
                "Your payment of $" . number_format($amountValue, 2) . " for Borrow Reference #" . $borrowId . " has been approved.",
                "payment"
            );
        }
    }

    public function findByIdWithDetails(int $paymentId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.payment_id, p.borrow_id, p.user_id, p.amount, p.status, p.payment_date, p.proof_image,
                b.status AS borrow_status, m.title AS book_title, u.username
            FROM payments p
            JOIN borrows b ON p.borrow_id = b.borrow_id
            JOIN media m ON b.media_id = m.media_id
            JOIN users u ON p.user_id = u.user_id
            WHERE p.payment_id = ?
            LIMIT 1
        ");

        $stmt->execute([$paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}