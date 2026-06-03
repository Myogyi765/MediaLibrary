<?php

namespace App\User\Presentation\Request;

class RegisterUserRequest
{
    public function rules(): array
    {
        return [

            'username_or_email' => [
                'required' => true,
                'min' => 3,
                'max' => 50,
            ],

            'email' => [
                'required' => true,
                'email' => true,
            ],

            'password' => [
                'required' => true,
                'min' => 8,
            ],

            'confirm_password' => [
                'required' => true,
                'match' => 'password',
            ],
        ];
    }
}