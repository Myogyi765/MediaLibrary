<?php 
if (!isset($db)) {
    $db = \App\DB\Database::getConnection();
}
require BASE_PATH . '/View/layout/header.php'; 

$currentUserId = isset($_SESSION['user']['user_id']) ? (int)$_SESSION['user']['user_id'] : 0;
$isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

// Querying all assigned system notifications for the authenticated recipient account
$allNotifications = [];
if ($currentUserId > 0) {
    $stmt = $db->prepare("SELECT * FROM notifications WHERE receiver_id = :user_id ORDER BY created_at DESC");
    $stmt->execute([':user_id' => $currentUserId]);
    $allNotifications = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .big-notif-container {
        max-width: 900px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 30px;
    }
    .page-title {
        color: #8e4a4a;
        font-weight: 700;
        border-bottom: 2px solid #8e4a4a;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .notif-box-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #eaeaea;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease-in-out;
    }
    .notif-box-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        background-color: #fdfdfd;
    }
    /* Expanded Unread Notification Styling */
    .notif-box-item.unread-big {
        background-color: #fff9e6;
        border-left: 6px solid #ffc107;
    }
    .notif-msg-text {
        font-size: 1.1rem;
        margin-bottom: 5px;
    }
    .notif-date-sub {
        font-size: 0.85rem;
        color: #777;
    }
</style>

<div class="container">
    <div class="big-notif-container">
        <div class="d-flex justify-content-between align-items-center page-title">
            <h2 class="m-0">🔔 All Notifications</h2>
            <span class="badge bg-secondary" id="total-notif-badge">Total: <?= count($allNotifications) ?></span>
        </div>

        <div id="big-page-notif-list">
            <?php if (!empty($allNotifications)) : ?>
                <?php foreach ($allNotifications as $noti) : 
                    // Dynamic Routing Redirection Assignment Logic
                    $targetUrl = BASE_URL . '/Public/index.php?page=my-borrows';
                    if ($isAdmin) {
                        $targetUrl = (strpos($noti['message'], 'transfer') !== false || strpos($noti['message'], 'Proof') !== false || strpos($noti['message'], 'payment') !== false)
                            ? BASE_URL . '/Public/index.php?page=admin-payments' 
                            : BASE_URL . '/Public/index.php?page=admin-reservations';
                    }
                    
                    $isUnread = ($noti['is_read'] == 0);
                ?>
                    <a href="<?= $targetUrl ?>" 
                       data-id="<?= $noti['id'] ?>" 
                       class="notif-box-item <?= $isUnread ? 'unread-big' : '' ?> full-page-notif-trigger">
                        <div>
                            <div class="notif-msg-text <?= $isUnread ? 'fw-bold text-dark' : 'text-muted' ?>">
                                <?= htmlspecialchars($noti['message']) ?>
                            </div>
                            <div class="notif-date-sub">
                                📅 <?= date('F j, Y, g:i a', strtotime($noti['created_at'])) ?>
                            </div>
                        </div>
                        <div>
                            <?php if ($isUnread) : ?>
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-7">New</span>
                            <?php else : ?>
                                <span class="badge bg-light text-muted px-3 py-2 rounded-pill border fs-7">Read</span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text-center py-5 text-muted fs-5">
                    📭 No notifications available yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Click event processing listener on full-page notification rows
    $(document).on('click', '.full-page-notif-trigger', function(e) {
        e.preventDefault();
        
        let redirectUrl = $(this).attr('href');
        let notiId = $(this).data('id');
        let currentItem = $(this);

        // Execute background network call to mutations engine endpoint to modify row state
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=mark_read',
            type: 'POST',
            data: { id: notiId },
            success: function(response) {
                // Update interface properties instantly into historical state styling
                currentItem.removeClass('unread-big');
                currentItem.find('.badge').removeClass('bg-warning text-dark').addClass('bg-light text-muted border').text('Read');
                
                // Complete deferred view switch migration sequence 
                window.location.href = redirectUrl;
            },
            error: function() {
                window.location.href = redirectUrl;
            }
        });
    });
});
</script>

<?php require BASE_PATH . '/View/layout/footer.php'; ?>