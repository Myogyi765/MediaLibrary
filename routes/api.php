<?php

use App\Catalog\Domain\Service\CatalogService;
use App\Catalog\Domain\Service\FormatService;
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
use App\User\Domain\Service\UserService;




$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'api/catalog':
        require_once BASE_PATH . '/App/Controller/api/CatalogApiController.php';
        $controller = new CatalogApiController($catalogService);
        $controller->index();
        break;

    case 'api/details':
        require_once BASE_PATH . '/App/Controller/api/DetailsApiController.php';
        $controller = new DetailsApiController($catalogService);
        $controller->show();
        break;

    case 'api/suggest':
        require_once BASE_PATH . '/App/Controller/api/SuggestApiController.php';
        $controller = new SuggestApiController($formatService);
        $controller->index();
        break;

    default:  // HOME PAGE
        $controller = new CatalogController($catalogService);
        $controller->home();
}
