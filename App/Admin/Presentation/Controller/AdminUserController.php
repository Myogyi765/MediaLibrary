<?php

namespace App\Admin\Presentation\Controller;

use App\User\Domain\Repository\UserInterface;

class AdminUserController
{
    private UserInterface $userRepo;

    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->getAll();

        require BASE_PATH . '/App/Admin/Presentation/view/users.php';
    }
}