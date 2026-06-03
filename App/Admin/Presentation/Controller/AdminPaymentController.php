<?php

namespace App\Admin\Presentation\Controller;

class AdminPaymentController
{
    public function index()
    {

        $payments = [];

        require BASE_PATH . '/App/Admin/Presentation/View/payments.php';
    }

    public function create()
    {
        require BASE_PATH . '/App/Admin/Presentation/View/payments-create.php';
    }

    public function store()
    {
      
        header('Location: /Public/index.php?page=admin-payments');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        $payment = []; // fetch from DB later

        require BASE_PATH . '/App/Admin/Presentation/View/payments-edit.php';
    }

    public function update()
    {
       

        header('Location: /Public/index.php?page=admin-payments');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;

     

        header('Location: /Public/index.php?page=admin-payments');
        exit;
    }
}