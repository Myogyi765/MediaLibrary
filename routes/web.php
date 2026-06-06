<?php

use App\DB\Database;

/* ================= CONTROLLERS ================= */
use App\Catalog\Presentation\Controller\CatalogController;
use App\Catalog\Presentation\Controller\DetailsController;
use App\Catalog\Presentation\Controller\SuggestController;

use App\User\Presentation\Controller\AuthController;
use App\User\Infrastructure\Persistence\UserRepository;

use App\Admin\Presentation\Controller\AdminUserController;
use App\Admin\Presentation\Controller\AdminReservationController;

use App\Borrow\Presentation\Controller\BorrowController;

use App\Payment\Presentation\Controller\PaymentController;
use App\Payment\Application\Service\PaymentService;
use App\Payment\Infrastructure\Persistence\PaymentRepository;

/* ================= SERVICES ================= */
use App\Borrow\Domain\Service\BorrowService;
use App\Borrow\Infrastructure\Persistence\BorrowRepository;

/* ================= REQUEST ================= */
use App\User\Presentation\Request\LoginRequest;
use App\User\Presentation\Request\RegisterUserRequest;
use App\User\Presentation\Validate\Validator;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Application\Service\FormatService;
use App\Admin\Presentation\Controller\AdminPaymentController;
use App\Admin\Presentation\Controller\AdminNotificationController;

/* ================= DB ================= */
$db = Database::getConnection();

/* ================= REPOSITORIES ================= */
$borrowRepo  = new BorrowRepository($db);
$paymentRepo = new PaymentRepository($db);
$userRepo    = new UserRepository($db);

/* ⚠️ ADD MISSING SERVICES */
$catalogRepo = new \App\Catalog\Infrastructure\Persistence\CatalogRepository($db);
$catalogService = new \App\Catalog\Domain\Service\CatalogService($catalogRepo);
$userService = new \App\User\Domain\Service\UserService($userRepo);

/* ================= SERVICES ================= */
$borrowService  = new BorrowService($borrowRepo, $paymentRepo);
$paymentService = new PaymentService($paymentRepo);

$page   = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';


/* ================= NOTIFICATION API ================= */
if ($page === 'notification_api') {

    $controller = new \App\Notification\Controller\NotificationApiController();

    switch ($action) {

        case 'send':
            $controller->send();
            break;

        case 'fetch':
            $controller->fetch();
            break;

        case 'mark_read':
            $controller->markRead();
            break;

        case 'mark_all_read':
            $controller->markAllRead();
            break;

        default:
            header('Content-Type: application/json');

            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid notification action'
            ]);

            break;
    }

    exit;
}

/* ================= 2. STANDARD PAGE ROUTER ================= */
switch ($page) {

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    case 'details':
        $controller = new DetailsController(
            $catalogService,
            $borrowRepo,
            $paymentRepo
        );
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService ?? null);
        $controller->index();
        break;

    case 'login':
        $controller = new AuthController($userService);
        $controller->login(new LoginRequest(), new Validator());
        break;

    case 'register':
        $controller = new AuthController($userService);
        $controller->register(new RegisterUserRequest(), new Validator());
        break;

    case 'logout':
        $controller = new AuthController($userService);
        $controller->logout();
        break;

    case 'admin-dashboard':
        $pageTitle = 'Admin Dashboard';
        $section = 'admin-dashboard';
        require BASE_PATH . '/view/admin-dashboard.php';
        break;

    case 'admin-users':
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

    case 'borrow':
        $controller = new BorrowController($borrowService);
        $controller->borrow();
        break;

    case 'return-book':
        $controller = new BorrowController($borrowService);
        $controller->returnBook();
        break;

    case 'my-borrows':
        $controller = new BorrowController($borrowService);
        $controller->myBorrows();
        break;

    case 'payment':
        $controller = new PaymentController($paymentService);
        $controller->show();
        break;

    case 'admin-payments':
        $controller = new AdminPaymentController($paymentRepo);
        $controller->index();
        break;

    case 'pay-process':
        $controller = new PaymentController($paymentService);
        $controller->process();
        break;

    case 'upload-proof':
        $controller = new PaymentController($paymentService);
        $controller->uploadProof();
        break;

    case 'invoice':
        $controller = new PaymentController($paymentService);
        $controller->invoice(); 
        break;

    case 'approve-payment':
        $controller = new AdminPaymentController($paymentRepo);
        $controller->approve((int)$_GET['id']);
        break;

 
case 'notifications':

    if (
        isset($_SESSION['user']['role']) &&
        $_SESSION['user']['role'] === 'admin'
    ) {

        require BASE_PATH . '/view/notifications.php';

    } else {

        $controller =
            new \App\Notification\Controller\UserNotificationController();

        $controller->index();
    }

    break;

    default:
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;
}