<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/
$headerNotifications = [];
$unreadCount = 0;

if (isset($_SESSION['user'])) {

    require_once BASE_PATH . '/App/Notification/Model/NotificationModel.php';

    if (!isset($db)) {
        $db = \App\DB\Database::getConnection();
    }

    if ($db) {
        $notifModel = new NotificationModel($db);

        $userId = (int)$_SESSION['user']['user_id'];

        $headerNotifications = $notifModel->getByUser($userId, 5);
        $unreadCount = $notifModel->countUnread($userId);
    }
}

$pageTitle = $pageTitle ?? 'Media Library';
$section   = $section ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<div class="page-container">

    <div class="content">

        <header class="header">

            <div class="wrapper">

                <h1 class="logo">
                    <a href="<?= BASE_URL ?>/Public/index.php">
                        <img src="<?= BASE_URL ?>/img/Brand-title.png" alt="Media Library">
                    </a>
                </h1>

                <ul class="nav">

                    <li class="<?= $section === 'books' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books">
                            <img src="<?= BASE_URL ?>/img/book.png" alt="">
                            Books
                        </a>
                    </li>

                    <li class="<?= $section === 'movies' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies">
                            <img src="<?= BASE_URL ?>/img/movie.png" alt="">
                            Movies
                        </a>
                    </li>

                    <li class="<?= $section === 'music' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music">
                            <img src="<?= BASE_URL ?>/img/music.png" alt="">
                            Music
                        </a>
                    </li>

                    <li class="<?= $section === 'suggest' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=suggest">
                            <img src="<?= BASE_URL ?>/img/suggestion.png" alt="">
                            Suggest
                        </a>
                    </li>


                    <?php if (isset($_SESSION['user'])) : ?>

                        <?php
                        $isAdmin = ($_SESSION['user']['role'] ?? 'user') === 'admin';

                        $dashboardUrl = $isAdmin
                            ? BASE_URL . '/Public/index.php?page=admin-dashboard'
                            : BASE_URL . '/Public/index.php?page=my-borrows';
                        ?>

                        <li class="user-name">
                            <a href="<?= $dashboardUrl ?>">
                                <?= htmlspecialchars($_SESSION['user']['username']) ?>
                            </a>
                        </li>

                        <li class="nav-item dropdown style-notif-container">

                            <a href="#" 
                               class="nav-link dropdown-toggle position-relative text-white" 
                               id="notifDropdown" 
                               role="button" 
                               data-bs-toggle="dropdown" 
                               aria-expanded="false"
                               style="display: inline-flex; align-items: center; text-decoration: none;">
                                🔔
                                <?php if ($unreadCount > 0) : ?>
                                    <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 0.65rem;">
                                        <?= $unreadCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-lg notification-dropdown-list" aria-labelledby="notifDropdown">
                                <li>
                                    <div class="dropdown-header border-bottom pb-2 mb-1">
                                        <strong>Notifications Inbox</strong>
                                    </div>
                                </li>

                                <?php if (empty($headerNotifications)) : ?>
                                    <li>
                                        <div class="dropdown-item text-muted text-center py-3">
                                            🔕 No notifications yet
                                        </div>
                                    </li>
                                <?php else : ?>
           <?php foreach ($headerNotifications as $notification) : ?>
                   <li>
            <div class="dropdown-item d-flex justify-content-between align-items-start gap-3 py-2 border-bottom <?= $notification['is_read'] ? 'bg-light text-muted' : 'bg-white font-weight-bold' ?>" style="white-space: normal; min-width: 280px; max-width: 340px;">
               <div style="flex: 1;">
            <div class="fw-bold text-dark" style="font-size: 13px; line-height: 1.3;">
               <?= htmlspecialchars($notification['title']) ?>
                                        </div>
          <div class="text-secondary mt-1" style="font-size: 12px; line-height: 1.4;">
              <?= htmlspecialchars($notification['message']) ?>
                           </div>
                           </div>
                                                
             <?php if (!$notification['is_read']) : ?>

          <a class="btn btn-sm btn-outline-success p-0 d-flex align-items-center justify-content-center rounded-circle" 
      style="width: 20px; height: 20px; font-size: 10px; flex-shrink: 0;"
    title="Mark as Read"

            href="<?= BASE_URL ?>/Public/index.php?page=mark-read&id=<?= $notification['id'] ?>">
                                      ✓
                                      </a>
                    <?php endif; ?>
                        </div>
                    </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                        </li>

                    <?php endif; ?>

                    <?php if (empty($_SESSION['user'])) : ?>
                        <li class="<?= $section === 'login' ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                  <img src="<?= BASE_URL ?>/img/login.png" alt="">
                     Login
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="<?= $section === 'logout' ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=logout">
                  <img src="<?= BASE_URL ?>/img/logout.png" alt="">
                         Logout
                         </a>
                        </li>
                    <?php endif; ?>

                </ul>

            </div>

        </header>

        <?php if (empty($hideSearch)) : ?>
            <div class="search">
                <div class="wrapper">
                    <form method="GET" action="<?= BASE_URL ?>/Public/index.php">
                        <input type="hidden" name="page" value="catalog">

                        <?php if (!empty($section)) : ?>
                            <input type="hidden" name="cat" value="<?= htmlspecialchars($section) ?>">
                        <?php endif; ?>

                        <label for="s">Search:</label>
                        <input type="text" name="s" id="s" value="<?= htmlspecialchars($_GET['s'] ?? '') ?>" placeholder="Search books, movies, music...">
                        <input type="submit" value="Go">
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <main id="content">