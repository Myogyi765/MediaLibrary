<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$headerNotifications = [];
$unreadCount = 0;
$currentUserId = 0;
$isAdmin = false;

if (isset($_SESSION['user'])) {
    require_once BASE_PATH . '/App/Notification/Repository/NotificationRepository.php';

    if (!isset($db)) {
        $db = \App\DB\Database::getConnection();
    }

    if ($db) {
        $notifModel = new \App\Notification\Repository\NotificationRepository($db);
        $currentUserId = (int)$_SESSION['user']['user_id'];
        $isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

        $headerNotifications = $notifModel->getByUser($currentUserId, 5);
        $unreadCount = $notifModel->countUnread($currentUserId);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* Modernized Notification Dropdown Styles */
        .notification-dropdown-list {
            width: 320px !important;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
        }
        .notif-click-trigger {
            font-size: 0.85rem;
            white-space: normal !important;
            word-wrap: break-word;
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 15px;
            display: block;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }
        .notif-click-trigger:hover {
            background-color: #f8f9fa;
        }
        .notif-click-trigger.unread {
            background-color: #fff9e6;
            font-weight: 600;
        }
    </style>
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
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books"><img src="<?= BASE_URL ?>/img/book.png" alt=""> Books</a>
                    </li>
                    <li class="<?= $section === 'movies' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies"><img src="<?= BASE_URL ?>/img/movie.png" alt=""> Movies</a>
                    </li>
                    <li class="<?= $section === 'music' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music"><img src="<?= BASE_URL ?>/img/music.png" alt=""> Music</a>
                    </li>
                    <li class="<?= $section === 'suggest' ? 'on' : '' ?>">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=suggest"><img src="<?= BASE_URL ?>/img/suggestion.png" alt=""> Suggest</a>
                    </li>

                    <?php if (!empty($_SESSION['user'])) : ?>
                        <li class="nav-item dropdown style-notif-container">
                            <a class="dropdown-toggle position-relative" href="#" id="notiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1.3rem;">
                                🔔
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="badge-count" style="<?= ($unreadCount > 0) ? '' : 'display:none;' ?>">
                                    <?= $unreadCount ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow notification-dropdown-list" aria-labelledby="notiDropdown">
                                <li class="dropdown-header border-bottom py-2">
                                    <span class="fw-bold text-dark">Latest Notifications</span>
                                </li>
                                
                                <div id="dropdown-noti-list">
                                    <?php if (!empty($headerNotifications)) : ?>
                                        <?php foreach ($headerNotifications as $noti) : 
                                            $unreadClass = ($noti['is_read'] == 0) ? 'unread' : '';
                                            
                                            // Next Page Routing Logic
                                            $targetUrl = BASE_URL . '/Public/index.php?page=my-borrows';
                                            if ($isAdmin) {
                                                $targetUrl = (strpos($noti['message'], 'transfer') !== false || strpos($noti['message'], 'Proof') !== false || strpos($noti['message'], 'payment') !== false)
                                                    ? BASE_URL . '/Public/index.php?page=admin-payments' 
                                                    : BASE_URL . '/Public/index.php?page=admin-reservations';
                                            }
                                        ?>
                                            <li>
                                                <a class="notif-click-trigger <?= $unreadClass ?>" href="<?= $targetUrl ?>" data-id="<?= $noti['id'] ?>">
                                                    <?= htmlspecialchars($noti['message']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <li id="no-notif-placeholder">
                                            <a class="dropdown-item text-muted text-center py-3" href="#">No notifications yet.</a>
                                        </li>
                                    <?php endif; ?>
                                </div>
                                
                                <li class="border-top text-center bg-light">
                                    <a class="dropdown-item py-2 fw-bold text-primary" href="<?= BASE_URL ?>/Public/index.php?page=notifications" style="font-size: 0.85rem;">
                                        👁️ See All Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>

                       <li>
    <a href="<?= BASE_URL ?>/Public/index.php?page=my-borrows" class="user-display-tag">
        👤 <?= htmlspecialchars($_SESSION['user']['username'] ?? 'User') ?>
    </a>
</li>
                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=logout"><img src="<?= BASE_URL ?>/img/logout.png" alt=""> Logout</a>
                        </li>
                    <?php else : ?>
                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=login" class="login-btn"><img src="<?= BASE_URL ?>/img/login.png" alt=""> Login</a>
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

        <main id="content"></main>

        <div class="wrapper mt-3">
            <div id="notification-alert" class="alert alert-success alert-dismissible fade show" style="display:none;" role="alert">
                <strong>Notification: </strong> <span id="notification-message"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <?php if ($currentUserId > 0) : ?>
                <!-- <?php 
                    $targetReceiverId = $isAdmin ? 5 : 2; 
                    $btnText = $isAdmin ? "Send Notification to User" : "Send Notification to Admin";
                    $msgText = $isAdmin ? "Admin has approved your dynamic request." : "A user has requested to borrow a book.";
                ?>
                <button id="send-btn" class="btn btn-warning btn-sm mb-3" 
                        data-sender="<?= $currentUserId ?>" 
                        data-receiver="<?= $targetReceiverId ?>" 
                        data-msg="<?= htmlspecialchars($msgText) ?>">
                    <?= $btnText ?>
                </button>
            <?php endif; ?> -->
        </div>

        <main id="content" class="wrapper mt-4">

<script>
$(document).ready(function() {
    const currentUserId = <?= (int)$currentUserId ?>;
    if (currentUserId === 0) return;

    let processedNotificationIds = new Set();
    let badgeCountElement = $('#badge-count');

    $('#dropdown-noti-list .notif-click-trigger').each(function() {
        const existingId = $(this).data('id');
        if (existingId !== undefined && existingId !== null) {
            processedNotificationIds.add(existingId.toString());
        }
    });

    function updateBadgeCount(delta) {
        let current = parseInt(badgeCountElement.text()) || 0;
        let next = Math.max(0, current + delta);
        badgeCountElement.text(next);
        if (next > 0) {
            badgeCountElement.show();
        } else {
            badgeCountElement.hide();
        }
    }

    function addNewNotificationToDropdown(noti) {
        const notificationId = noti.id.toString();
        if (processedNotificationIds.has(notificationId)) {
            return false;
        }

        processedNotificationIds.add(notificationId);

        let targetUrl = '<?= BASE_URL ?>/Public/index.php?page=my-borrows';
        <?php if ($isAdmin): ?>
            if (noti.message.includes('transfer') || noti.message.includes('Proof') || noti.message.includes('payment')) {
                targetUrl = '<?= BASE_URL ?>/Public/index.php?page=admin-payments';
            } else {
                targetUrl = '<?= BASE_URL ?>/Public/index.php?page=admin-reservations';
            }
        <?php endif; ?>

        let cleanElement = `
            <li>
                <a class="notif-click-trigger unread" href="${targetUrl}" data-id="${notificationId}">
                    ${noti.message}
                </a>
            </li>`;

        $('#dropdown-noti-list').prepend(cleanElement);
        return true;
    }

    $('#send-btn').on('click', function() {
        let senderId = $(this).data('sender');
        let receiverId = $(this).data('receiver');
        let message = $(this).data('msg');

        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=send',
            type: 'POST',
            data: { sender_id: senderId, receiver_id: receiverId, message: message },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert('Notification logged and transmitted successfully!');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });

    setInterval(function() {
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=fetch',
            type: 'GET',
            data: { user_id: currentUserId },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success' && response.data.length > 0) {
                    $('#no-notif-placeholder').remove();

                    response.data.forEach(function(noti) {
                        const added = addNewNotificationToDropdown(noti);
                        if (added) {
                            updateBadgeCount(1);
                        }

                        $('#notification-message').text(noti.message);
                        $('#notification-alert').fadeIn().delay(5000).fadeOut();
                    });
                }
            }
        });
    }, 2000);

    $(document).on('click', '.notif-click-trigger', function(e) {
        e.preventDefault();
        let targetPageUrl = $(this).attr('href');
        let notificationId = $(this).data('id');
        let clickedElement = $(this);

        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=mark_read',
            type: 'POST',
            data: { id: notificationId },
            success: function(response) {
                if (clickedElement.hasClass('unread')) {
                    clickedElement.removeClass('unread');
                    updateBadgeCount(-1);
                }
                window.location.href = targetPageUrl;
            },
            error: function() {
                window.location.href = targetPageUrl;
            }
        });
    });
});
</script>