<?php

use App\Catalog\Presentation\Controller\CatalogController;
use App\Catalog\Presentation\Controller\DetailsController;
use App\Catalog\Presentation\Controller\SuggestController;

use App\User\Presentation\Controller\AuthController;
use App\User\Infrastructure\Persistence\UserRepository;

use App\Admin\Presentation\Controller\AdminUserController;
use App\Admin\Presentation\Controller\AdminReservationController;
use App\Admin\Presentation\Controller\AdminPaymentController;

use App\Borrow\Presentation\Controller\BorrowController;
use App\Borrow\Domain\Service\BorrowService;
use App\Borrow\Infrastructure\Persistence\BorrowRepository;

use App\User\Presentation\Request\LoginRequest;
use App\User\Presentation\Request\RegisterUserRequest;
use App\User\Presentation\Validate\Validator;

use App\DB\Database;

$page = $_GET['page'] ?? 'home';

/* =========================
   GLOBAL DB + SERVICES
========================= */

$db = Database::getConnection();

/* Borrow System (IMPORTANT: only once) */
$borrowRepo = new BorrowRepository($db);
$borrowService = new BorrowService($borrowRepo);

switch ($page) {

    /* ================= CATALOG ================= */

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    /* ================= AUTH ================= */

    case 'login':
        require_once BASE_PATH . '/App/User/Presentation/Controller/AuthController.php';
        $controller = new AuthController($userService);
        $controller->login(new LoginRequest(), new Validator());
        break;

    case 'register':
        require_once BASE_PATH . '/App/User/Presentation/Controller/AuthController.php';
        $controller = new AuthController($userService);
        $controller->register(new RegisterUserRequest(), new Validator());
        break;

    case 'logout':
        require_once BASE_PATH . '/App/User/Presentation/Controller/AuthController.php';
        $controller = new AuthController($userService);
        $controller->logout();
        break;

    /* ================= ADMIN ================= */

    case 'admin-dashboard':
        $pageTitle = 'Admin Dashboard';
        $section = 'admin-dashboard';
        require BASE_PATH . '/View/admin-dashboard.php';
        break;

    case 'admin-users':
        $db = Database::getConnection();
        $userRepo = new UserRepository($db);

        $controller = new AdminUserController($userRepo);
        $controller->index();
        break;

    case 'admin-reservations':
        $controller = new AdminReservationController();
        $controller->index();
        break;



    case 'approve-borrow':
        $controller = new AdminReservationController();
        $controller->approve((int)$_GET['id']);
        break;

    case 'reject-borrow':
        $controller = new AdminReservationController();
        $controller->reject((int)$_GET['id']);
        break;

    /* ================= BORROW ================= */

    case 'borrow':
        $controller = new BorrowController($borrowService);
        $controller->borrow();
        break;

    case 'return-book':
        $controller = new BorrowController($borrowService);
        $controller->return();
        break;

    case 'my-borrows':
        $controller = new BorrowController($borrowService);
        $controller->myBorrows();
        break;

    /* ================= DEFAULT ================= */

    default:
        $controller = new CatalogController($catalogService);
        $controller->home();
}