<?php

namespace App\Borrow\Presentation\Controller;

use App\Borrow\Domain\Service\BorrowService;

class BorrowController
{
    private BorrowService $service;

    public function __construct(BorrowService $service)
    {
        $this->service = $service;
    }

    public function borrow()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/Public/index.php?page=login");
            exit;
        }

        $userId  = (int)$_SESSION['user']['user_id'];
        $mediaId = (int)($_POST['media_id'] ?? 0);

        if ($mediaId <= 0) {
            die("Invalid media ID");
        }

        $this->service->borrowBook($userId, $mediaId);

        header("Location: " . BASE_URL . "/Public/index.php?page=details&id=" . $mediaId);
        exit;
    }

    public function returnBook()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/Public/index.php?page=login");
            exit;
        }

        $borrowId = (int)($_GET['id'] ?? 0);

        if ($borrowId <= 0) {
            die("Invalid borrow ID");
        }

        $this->service->returnBook($borrowId);

        header("Location: " . BASE_URL . "/Public/index.php?page=my-borrows");
        exit;
    }

    public function myBorrows()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/Public/index.php?page=login");
            exit;
        }

        $userId = (int)$_SESSION['user']['user_id'];

        $borrows = $this->service->getUserBorrows($userId);

        require BASE_PATH . '/App/Borrow/Presentation/View/borrows.php';
    }
}