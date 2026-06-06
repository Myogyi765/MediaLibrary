<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($db)) {
    $db = \App\DB\Database::getConnection();
}

// Fetch current admin's ID directly from Session
$adminId = isset($_SESSION['user']['user_id']) ? (int)$_SESSION['user']['user_id'] : 2;

// Initial Database Fetch for historical notifications
$allNotifications = [];
if ($adminId > 0 && $db) {
    $stmt = $db->prepare("SELECT * FROM notifications WHERE receiver_id = :admin_id ORDER BY created_at DESC");
    $stmt->execute([':admin_id' => $adminId]);
    $allNotifications = $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notification Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .bg-custom { background-color: #8e4a4a; }
        
        /* Bell Dropdown System Box */
        .bell-trigger-btn {
            font-size: 1.6rem;
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            padding: 5px 10px;
            transition: transform 0.2s;
        }
        .bell-trigger-btn:hover { transform: scale(1.1); }
        
        .custom-drop-box {
            min-width: 340px;
            max-height: 420px;
            overflow-y: auto;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* Big Size Center Dashboard Layout */
        .big-notification-board {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
        }

        /* Large Interactive Notification Rows */
        .notif-card-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px; 
            margin-bottom: 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            color: #333;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .notif-card-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .notif-card-row.unread-state {
            background-color: #fff9f9;
            border-left: 6px solid #8e4a4a;
        }
        .notif-card-row.read-state {
            background-color: #ffffff;
            border-left: 6px solid #cbd5e1;
        }

        .notif-title-text {
            font-size: 1.2rem;
            margin-bottom: 6px;
        }
        .notif-timestamp {
            font-size: 0.88rem;
            color: #718096;
        }

        /* Massive Preview Modal Style override */
        .modal-content-big {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .modal-header-custom {
            background-color: #8e4a4a;
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Left Sidebar Navigation -->
        <div class="col-md-2 bg-custom text-white min-vh-100 p-3">
            <h4 class="mb-4 text-center pb-2 border-bottom">Admin Panel</h4>
            <div class="nav flex-column">
                <a class="nav-link text-white py-2" href="<?= BASE_URL ?>/Public/index.php?page=admin-users">👤 Users</a>
                <a class="nav-link text-white py-2" href="<?= BASE_URL ?>/Public/index.php?page=admin-reservations">📚 Reservations</a>
                <a class="nav-link text-white py-2" href="<?= BASE_URL ?>/Public/index.php?page=admin-payments">💳 Payments</a>
                <a class="nav-link text-danger mt-4 fw-bold" href="<?= BASE_URL ?>/Public/index.php?page=logout">🚪 Logout</a>
            </div>
        </div>

        <!-- Main Workspace Body -->
        <div class="col-md-10 p-4">
            
            <!-- Top Utility Bar with Real Drop Box Bell Button Component -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <h2 class="m-0">Dashboard Overview</h2>
                
                <!-- BELL SYSTEM DROPDOWN COMPONENT -->
                <div class="dropdown">
                    <button class="bell-trigger-btn dropdown-toggle no-toggle-arrow" type="button" id="bellDropBox" data-bs-toggle="dropdown" aria-expanded="false">
                        🔔<span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill fs-7" id="bell-badge-count">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end custom-drop-box p-2" aria-labelledby="bellDropBox">
                        <li class="dropdown-header border-bottom pb-2 mb-2"><span class="fw-bold text-dark">Quick Alert Drop-Box</span></li>
                        <!-- Small quick list container -->
                        <div id="quick-drop-list">
                            <li class="text-center py-3 text-muted" id="empty-drop-msg">No unread notifications.</li>
                        </div>
                    </ul>
                </div>
            </div>
            
            <!-- Statistical Counter Modules Grid -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <h6 class="text-muted">Total Users</h6>
                        <h3><?= htmlspecialchars((string)($totalUsers ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <h6 class="text-muted">Reservations</h6>
                        <h3><?= htmlspecialchars((string)($totalReservations ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <h6 class="text-muted">Books</h6>
                        <h3><?= htmlspecialchars((string)($totalBooks ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <h6 class="text-muted">Payments</h6>
                        <h3><?= htmlspecialchars((string)($totalPayments ?? 0)) ?></h3>
                    </div>
                </div>
            </div>

            <!-- Massive Notification Registry Layout -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="big-notification-board">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <h4 class="m-0 text-dark">📋 Master Notification Registry (Big Size)</h4>
                            <span class="badge bg-danger fs-5 px-3 py-2" id="admin-notif-count">0 Unread</span>
                        </div>
                        
                        <!-- Main Central Board Feed Container -->
                        <div id="admin-notification-board">
                            <?php if (!empty($allNotifications)) : ?>
                                <?php foreach ($allNotifications as $noti) : 
                                    $isUnread = ($noti['is_read'] == 0);
                                    $messageLower = strtolower($noti['message']);
                                    $targetUrl = BASE_URL . '/Public/index.php?page=admin-reservations';

                                    if (strpos($messageLower, 'payment') !== false || strpos($messageLower, 'proof') !== false || strpos($messageLower, 'receipt') !== false || strpos($messageLower, 'invoice') !== false) {
                                        $targetUrl = BASE_URL . '/Public/index.php?page=admin-payments';
                                    }
                                ?>
                                    <div class="notif-card-row <?= $isUnread ? 'unread-state' : 'read-state' ?> master-row-trigger" 
                                         data-id="<?= $noti['id'] ?>"
                                         data-msg="<?= htmlspecialchars($noti['message']) ?>"
                                         data-time="<?= $noti['created_at'] ?>"
                                         data-href="<?= $targetUrl ?>">
                                        <div>
                                            <div class="notif-title-text <?= $isUnread ? 'fw-bold text-dark' : 'text-muted' ?>">
                                                <?= htmlspecialchars($noti['message']) ?>
                                            </div>
                                            <div class="notif-timestamp">
                                                📅 <?= date('Y-m-d H:i:s', strtotime($noti['created_at'])) ?>
                                            </div>
                                        </div>
                                        <div class="badge-status-container">
                                            <?php if ($isUnread) : ?>
                                                <span class="badge bg-danger px-3 py-2 rounded-pill status-pill">🔴 Unread</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill status-pill">🟢 Read</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="p-5 text-center text-muted fs-5" id="no-admin-notif">📭 No active notification traffic logged.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div> 
</div>

<!-- FULL BOX LIGHTBOX MODAL PREVIEW WINDOW SYSTEM -->
<div class="modal fade" id="fullBoxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-big">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalBoxTitle">🔔 Message Full View Box</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="py-3 fs-3 text-dark border-bottom mb-3" id="modalBoxMessage">
                    Notification Message Details Appear Here...
                </div>
                <p class="text-muted fs-6" id="modalBoxTime"></p>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close Window</button>
                <a href="#" id="modalBoxActionBtn" class="btn btn-success px-4 fw-bold">Go to Management Screen ➡️</a>
            </div>
        </div>
    </div>
</div>

<!-- DYNAMIC CONTROLLER JAVASCRIPT LOGIC BLOCK -->
<script>
$(document).ready(function() {
    const adminUserId = <?= $adminId ?>;
    let processedIds = new Set();
    
    // Inventory current page markup keys on launch 
    $('.master-row-trigger').each(function() {
        processedIds.add($(this).data('id'));
    });

    // Calculates and syncs counters on badges 
    function updateStateCounters() {
        let unreadTotal = $('.master-row-trigger.unread-state').length;
        $('#admin-notif-count').text(unreadTotal + " Unread");
        
        if(unreadTotal > 0) {
            $('#bell-badge-count').text(unreadTotal).show();
            $('#empty-drop-msg').hide();
        } else {
            $('#bell-badge-count').hide();
            $('#empty-drop-msg').show();
        }
    }
    updateStateCounters();
    rebuildQuickDropMenu();

    // Rebuilds the Drop-Box contents based on current screen unread elements
    function rebuildQuickDropMenu() {
        $('#quick-drop-list').html('');
        let unreadItems = $('.master-row-trigger.unread-state');
        
        if(unreadItems.length === 0) {
            $('#quick-drop-list').html('<li class="text-center py-3 text-muted" id="empty-drop-msg">No unread notifications.</li>');
            return;
        }

        unreadItems.each(function() {
            let id = $(this).data('id');
            let msg = $(this).data('msg');
            let dropRow = `<li><a class="dropdown-item py-2 border-bottom drop-item-link text-wrap" href="#" data-target-id="${id}" style="font-size:0.85rem;">📌 ${msg}</a></li>`;
            $('#quick-drop-list').append(dropRow);
        });
    }

    // 1. Live Background Network Polling Loop (3 Seconds)
    function fetchLiveSystemTraffic() {
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=fetch',
            type: 'GET',
            data: { user_id: adminUserId },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success' && response.data.length > 0) {
                    $('#no-admin-notif').remove();
                    
                    response.data.forEach(function(noti) {
                        if (processedIds.has(noti.id)) return;
                        processedIds.add(noti.id);

                        let isUnread = (noti.is_read == 0);
                        let stateClass = isUnread ? 'unread-state' : 'read-state';
                        let fontClass = isUnread ? 'fw-bold text-dark' : 'text-muted';
                        let badgeHtml = isUnread ? '<span class="badge bg-danger px-3 py-2 rounded-pill status-pill">🔴 Unread</span>' : '<span class="badge bg-secondary px-3 py-2 rounded-pill status-pill">🟢 Read</span>';
                        
                        let targetUrl = '<?= BASE_URL ?>/Public/index.php?page=admin-reservations';
                        let messageLower = noti.message.toLowerCase();
                        if (messageLower.includes('payment') || messageLower.includes('proof') || messageLower.includes('receipt') || messageLower.includes('invoice')) {
                            targetUrl = '<?= BASE_URL ?>/Public/index.php?page=admin-payments';
                        }

                        let element = `
                            <div class="notif-card-row ${stateClass} master-row-trigger" 
                                 data-id="${noti.id}" 
                                 data-msg="${noti.message}" 
                                 data-time="${noti.created_at}" 
                                 data-href="${targetUrl}">
                                <div>
                                    <div class="notif-title-text ${fontClass}">${noti.message}</div>
                                    <div class="notif-timestamp">📅 ${noti.created_at}</div>
                                </div>
                                <div class="badge-status-container">${badgeHtml}</div>
                            </div>`;
                        
                        $('#admin-notification-board').prepend(element);
                    });
                    
                    updateStateCounters();
                    rebuildQuickDropMenu();
                }
            }
        });
    }
    setInterval(fetchLiveSystemTraffic, 3000);

    // 2. Click Handler for Drop-box list item links
    $(document).on('click', '.drop-item-link', function(e) {
        e.preventDefault();
        let targetId = $(this).data('target-id');
        // Find match inside big list and trigger full-box modal pop
        $(`.master-row-trigger[data-id="${targetId}"]`).click();
    });

    // 3. MASTER CLICK TRIGGER: Opens full box view and marks as read on server side
    $(document).on('click', '.master-row-trigger', function() {
        let rowElement = $(this);
        let id = rowElement.data('id');
        let message = rowElement.data('msg');
        let time = rowElement.data('time');
        let actionPage = rowElement.data('href');

        // Populate Modal Box content immediately
        $('#modalBoxMessage').text(message);
        $('#modalBoxTime').text("Logged Timeline: " + time);
        $('#modalBoxActionBtn').attr('href', actionPage);

        // Call server API instantly to transition flag from unread -> read
        if(rowElement.hasClass('unread-state')) {
            $.ajax({
                url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=mark_read',
                type: 'POST',
                data: { id: id },
                success: function() {
                    // Update main panel row architecture instantly to match read state parameters
                    rowElement.removeClass('unread-state').addClass('read-state');
                    rowElement.find('.notif-title-text').removeClass('fw-bold text-dark').addClass('text-muted');
                    rowElement.find('.badge-status-container').html('<span class="badge bg-secondary px-3 py-2 rounded-pill status-pill">🟢 Read</span>');
                    
                    updateStateCounters();
                    rebuildQuickDropMenu();
                }
            });
        }

        // Display Full View Box to Admin
        var fullBoxInstance = new bootstrap.Modal(document.getElementById('fullBoxModal'));
        fullBoxInstance.show();
    });
});
</script>

</body>
</html>