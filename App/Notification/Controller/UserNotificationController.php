<?php

namespace App\Notification\Controller;

use App\DB\Database;
use App\Notification\Service\NotificationService;
use App\Notification\Repository\NotificationRepository;

class UserNotificationController
{
    private NotificationService $service;

    public function __construct()
    {
        $db = Database::getConnection();

        $repository =  new NotificationRepository($db);

        $this->service =   new NotificationService(
                $repository
            );
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {

            header(
                'Location: '
                . BASE_URL
                . '/Public/index.php?page=login'
            );

            exit;
        }

        $userId =
            (int)$_SESSION['user']['user_id'];

        $notifications =
            $this->service->getAll(
                $userId
            );

        require BASE_PATH
            . '/view/user-notifications.php';
    }
}