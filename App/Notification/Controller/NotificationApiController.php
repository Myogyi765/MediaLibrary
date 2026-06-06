<?php
namespace App\Notification\Controller;

use App\Notification\Repository\NotificationRepository;
use App\DB\Database;

use App\Notification\Service\NotificationService;


class NotificationApiController {
    

    private NotificationService $service;
    public function __construct() {
        $db = Database::getConnection();
        $repository = new NotificationRepository($db);
        $this->service = new NotificationService($repository);

    }

    /**
     * Dispatch and save a new real-time notification payload
     */
    public function send() {
        header('Content-Type: application/json');
        
        $senderId = isset($_POST['sender_id']) ? (int)$_POST['sender_id'] : null;
        $receiverId = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : null;
        $message = $_POST['message'] ?? '';

        if ($senderId && $receiverId && $message) {
           $this->service->send(
    $senderId,
    $receiverId,
    $message
);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incomplete parameter data provided.']);
        }
        exit;
    }

    /**
     * Fetch all pending unread records for an active system entity context
     */
    public function fetch() {
        header('Content-Type: application/json');
        
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

        if ($userId) {
          $data = $this->service->getUnread($userId);
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is a required parameter.']);
        }
        exit;
    }

    public function markRead() {
        header('Content-Type: application/json');
        $notificationId = isset($_POST['id']) ? (int)$_POST['id'] : null;

        if ($notificationId) {
           $this->service->markRead($notificationId);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Notification ID is required.']);
        }
        exit;
    }

    public function markAllRead() {
        header('Content-Type: application/json');
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

        if ($userId) {
          $this->service->markAllRead($userId);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required.']);
        }
        exit;
    }
}