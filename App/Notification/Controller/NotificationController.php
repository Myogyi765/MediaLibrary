<?php

class NotificationController
{
    private $model;

    public function __construct($notificationModel)
    {
        $this->model = $notificationModel;
    }

    public function index($userId)
    {
        return $this->model->getByUser($userId);
    }

    public function markRead($id)
    {
        return $this->model->markAsRead($id);
    }

    public function unreadCount($userId)
    {
        return $this->model->countUnread($userId);
    }
}