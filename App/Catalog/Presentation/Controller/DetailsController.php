<?php

namespace App\Catalog\Presentation\Controller;

use App\Catalog\Domain\Service\CatalogService;
use App\Borrow\Infrastructure\Persistence\BorrowRepository;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;

class DetailsController
{
    private CatalogService $catalogService;
    private BorrowRepository $borrowRepository;
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(
        CatalogService $catalogService,
        BorrowRepository $borrowRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->catalogService   = $catalogService;
        $this->borrowRepository  = $borrowRepository;
        $this->paymentRepository = $paymentRepository;
    }

public function show()
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: ' . BASE_URL . '/Public/index.php?page=catalog');
        exit;
    }

    $item = $this->catalogService->singleItemArray($id);

    if (!$item) {
        header('Location: ' . BASE_URL . '/Public/index.php?page=catalog');
        exit;
    }

    $userId = $_SESSION['user']['user_id'] ?? null;
    $borrow = null;

    if ($userId) {
        $borrow = $this->borrowRepository->findByUserAndMedia($userId, $id);

        if ($borrow) {
            $payment = $this->paymentRepository->findByBorrowIdWithTitle((int) $borrow['borrow_id']);

       
            $borrow['payment'] = $payment ?: [
    'title' => $item['title'] ?? 'Unknown',
    'status' => null
];
        }
    }
     


    $section = $item['category'] ?? 'details';
    $pageTitle = $item['title'] ?? 'Details';

    require BASE_PATH . '/view/details.php';
}
}