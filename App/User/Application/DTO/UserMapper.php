<?php

namespace App\User\Application\DTO;

use App\User\Domain\Entity\User;
class UserMapper
{
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getRole()
        );
    }

    public static function toArray(UserDTO $dto): array
    {
        return $dto->toArray();
    }
}
