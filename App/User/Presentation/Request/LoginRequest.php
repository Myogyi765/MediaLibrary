<?php

namespace App\User\Presentation\Request;

class LoginRequest
{
    public function rules(): array
    {
        return [

            'username_or_email' => [
                'required' => true,
                'min' => 3,
                'max' => 50,
            ],

            'password' => [
                'required' => true,
                'min' => 8,
            ],

        ];
    }
}