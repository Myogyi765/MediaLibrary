<?php

namespace App\User\Presentation\Controller;

use App\User\Domain\Service\UserService;
use App\User\Presentation\Request\LoginRequest;
use App\User\Presentation\Request\RegisterUserRequest;
use App\User\Presentation\Validate\Validator;

class AuthController
{
    private UserService $userService;
    // private Validator $validator;
    // private LoginRequest $loginRequest;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function login(LoginRequest $loginRequest, Validator $validator): void
    {
        $pageTitle = 'Login';
        $section = 'login';
        $hideSearch = true;

        $usernameOrEmail = '';
        $errors = [];
        $errorMessage = null;
        $successMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
            $password = $_POST['password'] ?? '';

            // validate
            // $request = new LoginRequest();
            // $validator = new Validator();

            $isValid = $validator->validate($_POST, $loginRequest->rules());

            if (!$isValid) {
                $errors = $validator->errors();

                require BASE_PATH . '/view/login.php';
                return; // 🔥 IMPORTANT FIX
            }

            $user = $this->userService->authenticate($usernameOrEmail, $password);

            if ($user === null) {
                $errors['error_message'] = 'Invalid login credentials.';

                require BASE_PATH . '/view/login.php';
                return;
            }
            

           $_SESSION['user'] = $user->toArray();
$_SESSION['success_message'] = 'Login successful!';

$isAdmin = ($_SESSION['user']['role'] ?? 'user') === 'admin';

if ($isAdmin) {
    header('Location: ' . BASE_URL . '/Public/index.php?page=admin-dashboard');
    exit;
}

header('Location: ' . BASE_URL . '/Public/index.php?page=home');
exit;
        }

        require BASE_PATH . '/view/login.php';
    }

    public function register(RegisterUserRequest $request, Validator $validator): void
    {
        $pageTitle = 'Register';
        $section = 'register';
        $hideSearch = true;

        $username = '';
        $email = '';
        $successMessage = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');

            $isValid = $validator->validate(
                $_POST,
                $request->rules()
            );

            if (!$isValid) {

                $errors = $validator->errors();
            } else {

                $response = $this->userService->register([

                    'username' => $username,

                    'email' => $email,

                    'password' => $_POST['password'] ?? '',

                    'confirm_password' =>
                    $_POST['confirm_password'] ?? ''
                ]);

                if ($response->success) {

                    $successMessage =
                        $response->message;

                    $username = '';
                    $email = '';
                } else {

                    $errors = $response->data ?? [];
                }
            }
        }

        require BASE_PATH . '/view/register.php';
    }

    public function logout(): void
    {
        session_unset();

        session_destroy();

        header(
            'Location: '
                . BASE_URL
                . '/Public/index.php?page=index'
        );

        exit;
    }
}
