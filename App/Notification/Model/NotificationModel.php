<?php

class NotificationModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // CREATE notification with explicit column declarations
    public function create(?int $userId, string $title, string $message, string $type = 'info'): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, title, message, type, is_read, created_at)
                VALUES (?, ?, ?, ?, 0, NOW())
            ");
            return $stmt->execute([$userId, $title, $message, $type]);
        } catch (\PDOException $e) {
            // Logs error to PHP error log if something fails natively
            error_log("Notification Insertion Failed: " . $e->getMessage());
            return false;
        }
    }

    // GET user notifications
    public function getByUser(int $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifications
            WHERE user_id = ? OR user_id IS NULL
            ORDER BY created_at DESC
            LIMIT ?"
        );
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // GET all notifications (admin/audit view)
    public function getAll(int $limit = 15): array
    {
        $stmt = $this->db->prepare("
            SELECT n.*, u.username
            FROM notifications n
            LEFT JOIN users u ON n.user_id = u.user_id
            ORDER BY n.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // MARK AS READ matching your specific 'id' primary key column layout
    public function markAsRead(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE notifications SET is_read = 1 WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    // UNREAD COUNT
    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM notifications 
            WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0
        ");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}