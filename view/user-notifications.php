<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require BASE_PATH . '/view/layout/header.php';

$userId = (int)($_SESSION['user']['user_id'] ?? 0);

$stmt = $db->prepare("
    SELECT *
    FROM notifications
    WHERE receiver_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$userId]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$unreadCount = 0;

foreach ($notifications as $n) {
    if ($n['is_read'] == 0) {
        $unreadCount++;
    }
}
?>

<style>
.user-notification-board{
    max-width:1100px;
    margin:40px auto;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.notification-card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    margin-bottom:18px;
    border:1px solid #e5e7eb;
    transition:.3s;
    cursor:pointer;
}

.notification-card:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.notification-card.unread{
    border-left:6px solid #ff9800;
    background:#fffaf0;
}

.notification-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:10px;
}

.notification-time{
    color:#777;
    font-size:14px;
}

.notification-footer{
    margin-top:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.page-title{
    margin:0;
}

.unread-badge{
    font-size:14px;
    padding:8px 15px;
}

.empty-box{
    background:#fff;
    padding:40px;
    border-radius:15px;
    text-align:center;
    border:1px solid #ddd;
}

.mark-all-btn{
    margin-bottom:20px;
}
</style>

<div class="user-notification-board">

    <div class="page-header">
        <h2 class="page-title">
            🔔 My Notifications
        </h2>

        <span class="badge bg-danger unread-badge">
            <?= $unreadCount ?> Unread
        </span>
    </div>

    <?php if(!empty($notifications)): ?>

        <div class="text-end mark-all-btn">
            <button id="mark-all-read" class="btn btn-primary">
                ✓ Mark All Read
            </button>
        </div>

        <?php foreach($notifications as $noti): ?>

            <?php

            $targetUrl = BASE_URL . '/Public/index.php?page=my-borrows';

            if (
                stripos($noti['message'],'payment') !== false ||
                stripos($noti['message'],'approved') !== false
            ) {
                $targetUrl = BASE_URL . '/Public/index.php?page=my-borrows';
            }

            ?>

            <a href="<?= $targetUrl ?>"
               class="notification-card <?= $noti['is_read'] == 0 ? 'unread' : '' ?> user-noti-item"
               data-id="<?= $noti['id'] ?>"
               style="display:block;text-decoration:none;color:#333;">

                <div class="notification-title">
                    <?= htmlspecialchars($noti['message']) ?>
                </div>

                <div class="notification-time">
                    📅 <?= date('Y-m-d H:i:s', strtotime($noti['created_at'])) ?>
                </div>

                <div class="notification-footer">

                    <?php if($noti['is_read'] == 0): ?>
                        <span class="badge bg-warning text-dark">
                            Unread
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">
                            Read
                        </span>
                    <?php endif; ?>

                   

                </div>

            </a>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-box">
            <h4>📭 No Notifications Found</h4>
            <p class="text-muted mb-0">
                You don't have any notifications yet.
            </p>
        </div>

    <?php endif; ?>

</div>

<script>
$(document).on('click', '.user-noti-item', function(e){

    e.preventDefault();

    let url = $(this).attr('href');
    let id  = $(this).data('id');

    $.ajax({
        url:'<?= BASE_URL ?>/Public/index.php?page=notification_api&action=mark_read',
        type:'POST',
        data:{id:id},
        complete:function(){
            window.location.href = url;
        }
    });

});


$('#mark-all-read').on('click', function(){

    $.ajax({
        url:'<?= BASE_URL ?>/Public/index.php?page=notification_api&action=mark_all_read',
        type:'POST',
        data:{
            user_id:'<?= $userId ?>'
        },
        success:function(){
            location.reload();
        }
    });

});
</script>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>