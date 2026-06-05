<?php

namespace App\Borrow\Infrastructure\Persistence;

use App\Borrow\Domain\Entity\Borrow;
use App\Borrow\Domain\Repository\BorrowRepositoryInterface;
use App\Catalog\Infrastructure\Persistence\BaseRepository;
use PDO;

class BorrowRepository extends BaseRepository implements BorrowRepositoryInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'borrows', 'borrow_id');
    }


    public function findByUserAndMedia(int $userId, int $mediaId)
{
    $stmt = $this->db->prepare("
        SELECT * FROM borrows
        WHERE user_id = ? AND media_id = ?
        ORDER BY borrow_id DESC
        LIMIT 1
    ");

    $stmt->execute([$userId, $mediaId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    protected function mapToModel(array $row): object
    {
        return Borrow::fromArray($row);
    }

public function findByUserId(int $userId)
{
    $stmt = $this->db->prepare("
        SELECT
            b.borrow_id,
            b.user_id,
            b.media_id,
            b.borrow_date,
            b.return_date,
            b.status AS status,
            m.title,

            p.payment_id,
            p.status AS payment_status

        FROM borrows b
        JOIN media m ON m.media_id = b.media_id
        LEFT JOIN payments p ON p.borrow_id = b.borrow_id
        WHERE b.user_id = ?
        ORDER BY b.borrow_id DESC
    ");

    $stmt->execute([$userId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert into structured format for your view
    foreach ($rows as &$row) {
        $row['payment'] = [
            'payment_id' => $row['payment_id'] ?? null,
            'status'     => $row['payment_status'] ?? null
        ];
    }

    return $rows;
}

    public function findActiveBorrows(int $userId)
    {
        return $this->getAll([
            'user_id' => $userId,
            'status' => 'borrowed'
        ]);
    }
   public function getAllBorrows()
{
    $sql = "
        SELECT
            b.borrow_id,
            b.user_id,
            b.media_id,
            b.borrow_date,
            b.return_date,
            b.status,
            u.username,
            m.title
        FROM borrows b
        JOIN users u ON u.user_id = b.user_id
        JOIN media m ON m.media_id = b.media_id
        ORDER BY b.borrow_id DESC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}