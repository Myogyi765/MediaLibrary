<?php
namespace App\Notification\Repository;

use PDO;

class NotificationRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

   
    public function createNotification(
        $senderId, 
        $receiverId, 
        $message)    {

        $stmt = $this->db->prepare("INSERT INTO notifications
         (sender_id, receiver_id, message, is_read)
          VALUES (?, ?, ?, 0)");

        return $stmt->execute([
            $senderId,
             $receiverId, 
             $message]);
    }


   public function getUnreadNotifications(
             $userId)
{
    $stmt = $this->db->prepare("
        SELECT * 
        FROM notifications 
        WHERE receiver_id = ? AND is_read = 0 
        ORDER BY id DESC
    ");

    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getByUser(
        $userId, $limit = 5) {
            
        $stmt = $this->db->prepare("
        SELECT * FROM notifications
         WHERE receiver_id = ? ORDER BY id
          DESC LIMIT ?
          ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread(
        $userId) {

        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM notifications 
        WHERE receiver_id = ?
         AND is_read = 0
         ");

        $stmt->execute([$userId]);

        return (int)$stmt->fetchColumn();
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM notifications
            WHERE receiver_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId)
    {
        $stmt = $this->db->prepare("
        UPDATE notifications SET is_read = 1 WHERE id = ?
        ");

        return $stmt->execute([$notificationId]);
    }

    public function markAllRead($userId)
    {
        $stmt = $this->db->prepare("
        UPDATE notifications SET is_read = 1
         WHERE receiver_id = ? AND is_read = 0
         ");

        return $stmt->execute([$userId]);
    }
}
