<?php

namespace App\Borrow\Presentation\Controller;

use App\Borrow\Domain\Service\BorrowService;

class BorrowController
{
    public function __construct(
        private BorrowService $service
    ) {}

    public function borrow()
    {
        $userId = $_SESSION['user']['user_id'];
        $mediaId = $_POST['media_id'];

        $this->service->borrowBook($userId, $mediaId);

       header("Location: " . BASE_URL . "/Public/index.php?page=home");
exit;
    }

    public function return()
    {
        $borrowId = $_GET['id'];

        $this->service->returnBook($borrowId);

        header("Location: index.php?page=borrows");
        exit;
    }

    public function myBorrows()
    {
        $userId = $_SESSION['user']['user_id'];

        $borrows = $this->service->getUserBorrows($userId);

        require BASE_PATH . '/App/Borrow/Presentation/view/borrows.php';
    }
}