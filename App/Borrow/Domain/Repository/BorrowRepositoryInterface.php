<?php

namespace App\Borrow\Domain\Repository;

use App\Catalog\Domain\Repository\BaseInterface;

interface BorrowRepositoryInterface extends BaseInterface
{
    public function findByUserId(int $userId);
    public function findActiveBorrows(int $userId);
}