<?php
namespace App\User\Domain\Repository;

use App\Catalog\Domain\Repository\BaseInterface;

interface UserInterface extends BaseInterface
{
    public function findByUsername(string $username);
    public function findByEmail(string $email);
       public function getAll(array $criteria = [], $limit = null, $offset = null);
}
