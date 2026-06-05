<?php

namespace App\Borrow\Domain\Repository;

use App\Catalog\Domain\Repository\BaseInterface;

interface BorrowRepositoryInterface extends BaseInterface
{
    public function findByUserId(int $userId);
    public function findActiveBorrows(int $userId);
    public function findByUserAndMedia(int $userId, int $mediaId);
     public function create(array $data);
    public function update(int $id, array $data);
}