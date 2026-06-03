<?php

namespace App\User\Infrastructure\Persistence;

use App\User\Domain\Repository\UserInterface;
use App\User\Domain\Entity\User;
use App\Catalog\Infrastructure\Persistence\BaseRepository;
use PDO;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'users', 'user_id');
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToModel($row) : null;
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToModel($row) : null;
    }

    public function findAll(): array
{
    $stmt = $this->db->query("SELECT * FROM users ORDER BY user_id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(fn($row) => $this->mapToModel($row), $rows);
}

    protected function mapToModel(array $row): object
    {
        return User::fromArray($row);
    }
}