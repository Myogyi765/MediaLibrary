<?php

namespace App\Admin\Presentation\Controller;

use App\Notification\Model\NotificationModel;

class AdminNotificationController
{
    private $db;

    // Pass the raw DB connection into the controller since you don't use a separate repository interface here
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $db = $this->db; // Extracted to remain compatible with your view's internal isset($db) block
        
        $pageTitle = 'Notifications Audit Log';
        $section = 'admin-notifications';

        // Include the standalone view file
     require BASE_PATH . '/App/Admin/Presentation/View/notifications.php';
    }
}