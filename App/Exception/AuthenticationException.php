<?php

namespace App\Exception;

use Exception;

class AuthenticationException extends Exception
{

  private array $errors;

    public function __construct(
        string $message = '',
        array $errors = []
    ) {
        parent::__construct($message);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}