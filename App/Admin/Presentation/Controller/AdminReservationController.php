<?php

namespace App\Admin\Presentation\Controller;

use App\DB\Database;
use App\Borrow\Infrastructure\Persistence\BorrowRepository;

class AdminReservationController
{
    public function index()
    {
        $db = Database::getConnection();
        $repo = new BorrowRepository($db);

        $borrows = $repo->getAllBorrows();

        require BASE_PATH . '/App/Admin/Presentation/View/reservations.php';
    }


     public function approve(int $id)
    {
        $db = Database::getConnection();
        $repo = new BorrowRepository($db);

        $repo->update($id, [
            'status' => 'approved'
        ]);

        header("Location: " . BASE_URL . "/Public/index.php?page=admin-reservations");
        exit;
    }

    public function reject(int $id)
    {
        $db = Database::getConnection();
        $repo = new BorrowRepository($db);

        $repo->update($id, [
            'status' => 'rejected'
        ]);

        header("Location: " . BASE_URL . "/Public/index.php?page=admin-reservations");
        exit;
    }

    
    public function create()
    {
        require BASE_PATH . '/App/Admin/Presentation/View/reservations-create.php';
    }

    public function store()
    {
        header('Location: /Public/index.php?page=admin-reservations');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        $reservation = [];

        require BASE_PATH . '/App/Admin/Presentation/View/reservations-edit.php';
    }

    public function update()
    {
        header('Location: /Public/index.php?page=admin-reservations');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;

        header('Location: /Public/index.php?page=admin-reservations');
        exit;
    }
}