<?php

namespace App\Notification\Service;

use App\Notification\Repository\NotificationRepository;

class NotificationService
{
    private NotificationRepository $repository;

    public function __construct(
        NotificationRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function send(
        int $senderId,
        int $receiverId,
        string $message
    ): bool {

        return $this->repository
            ->createNotification(
                $senderId,
                $receiverId,
                $message
            );
    }

    public function getUnread(
        int $userId
    ): array {

        return $this->repository
            ->getUnreadNotifications(
                $userId
            );
    }

    public function getAll(
        int $userId
    ): array {

        return $this->repository
            ->getAllByUser(
                $userId
            );
    }

    public function markRead(
        int $notificationId
    ): bool {

        return $this->repository
            ->markAsRead(
                $notificationId
            );
    }

    public function markAllRead(
        int $userId
    ): bool {

        return $this->repository
            ->markAllRead(
                $userId
            );
    }

    public function countUnread(
        int $userId
    ): int {

        return $this->repository
            ->countUnread(
                $userId
            );
    }
}