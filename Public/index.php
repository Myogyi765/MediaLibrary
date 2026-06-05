<?php

use App\Catalog\Presentation\Controller\api\CatalogApiController;
use App\Catalog\Presentation\Controller\api\DetailsApiController;
use App\Catalog\Presentation\Controller\api\SuggestApiController;
use App\User\Presentation\Controller\AuthController;
use App\Catalog\Presentation\Controller\CatalogController;
use App\Catalog\Presentation\Controller\DetailsController;
use App\Catalog\Presentation\Controller\SuggestController;
use App\DB\Database;
use App\Catalog\Infrastructure\Persistence\CatalogRepository;
use App\Catalog\Infrastructure\Persistence\FormatRepository;
use App\User\Infrastructure\Persistence\UserRepository;
use App\Catalog\Domain\Service\CatalogService;
use App\Catalog\Domain\Service\FormatService;
use App\User\Domain\Service\UserService;
use App\Core\GlobalExceptionHandler;
/**
 * Main application entry point.
 * Initializes dependencies, services, and application routing.
 */

/*
 * //Report simple running errors
 * error_reporting(E_ALL);
 * //Make sure they are on screen
 * ini_set('display_errors',1);
 * //HTML formatted errors
 * ini_set('html_errors',1);
 *         OR
 * use @ in front of error
 */
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/vendor/stripe-php/init.php';

GlobalExceptionHandler::register();
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* BUILD SHARED OBJECTS */

$db = Database::getConnection();

/* Repositories */
$catalogRepo = new CatalogRepository($db);
$formatRepo = new FormatRepository($db);

/* Services */
$catalogService = new CatalogService($catalogRepo);
$formatService = new FormatService($formatRepo);
$userRepo = new UserRepository($db);
$userService = new UserService($userRepo);


$page = $_GET['page'] ?? 'home';
if (strpos($page, 'api/') === 0) {
    require_once BASE_PATH . '/routes/api.php';
} else {
    require_once BASE_PATH . '/routes/web.php';
}
