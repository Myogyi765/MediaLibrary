<style>
/* Page Layout styles */
body {
    background: #d8d1ce;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Page Title Headers */
.page-title {
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    position: relative;
}

.page-title::after {
    content: "";
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #ef7d7d, #ffb3b3);
    display: block;
    margin: 10px auto 0;
    border-radius: 50px;
}

/* Master Layout Grid Container */
.table-container {
    background: #d0d8da;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    overflow-x: auto;
}

/* Data Tables */
.borrow-table {
    width: 100%;
    border-collapse: collapse;
}

/* Header Columns */
.borrow-table thead {
    background: linear-gradient(135deg, #ef7d7d, #ff9a9a);
    color: white;
}

.borrow-table th {
    padding: 16px;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 1px;
    font-weight: 600;
}

/* Body Content Rows */
.borrow-table td {
    padding: 16px;
    color: #35373c;
    font-weight: 400;
}

.borrow-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #edf2f7;
}

.borrow-table tbody tr:hover {
    background: #fff5f5;
    transform: scale(1.01);
}

/* Status Badges */
.status {
    display: inline-block;
    padding: 7px 15px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #3920c6;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    margin-bottom: 20px;
    transition: 0.3s;
}
.pending {
    background: #fff3cd;
    color: #856404;
}

.approved {
    background: #d4edda;
    color: #155724;
}

.rejected {
    background: #f8d7da;
    color: #721c24;
}

.returned {
    background: #d1ecf1;
    color: #0c5460;
}

/* Operational Action Buttons */
.action-btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-size: 13px;
    font-weight: 600;
    transition: 0.3s ease;
    border: none;
    cursor: pointer;
}

.approve-btn {
    background: linear-gradient(135deg, #28a745, #34d058);
}

.approve-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(40,167,69,0.3);
}

.reject-btn {
    background: linear-gradient(135deg, #dc3545, #ff5b6b);
}

.reject-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(220,53,69,0.3);
}

/* Completed Inactive Elements Styling */
.completed {
    color: #6c757d;
    font-weight: bold;
}

/* Mobile Media Breakpoint Responsive Optimization */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }

    .borrow-table th,
    .borrow-table td {
        padding: 12px;
        font-size: 14px;
    }

    .action-btn {
        display: block;
        margin-bottom: 5px;
        text-align: center;
    }
}
</style>

<h2 class="page-title">
    Borrow Management
</h2>
<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>/Public/index.php?page=admin-dashboard" class="back-btn">
        ← Back to Dashboard
    </a>
</div>

<div class="table-container">
    <table class="borrow-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Media Title</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($borrows)): ?>
                <?php foreach ($borrows as $borrow): ?>
                    <tr>
                        <td><?= htmlspecialchars($borrow['borrow_id']) ?></td>
                        <td><?= htmlspecialchars($borrow['username']) ?></td>
                        <td><?= htmlspecialchars($borrow['title']) ?></td>
                        <td><?= htmlspecialchars($borrow['borrow_date']) ?></td>
                        <td>
                            <?= $borrow['return_date'] ? htmlspecialchars($borrow['return_date']) : '-' ?>
                        </td>
                        <td>
                            <span class="status <?= htmlspecialchars($borrow['status']) ?>">
                                <?= ucfirst(htmlspecialchars($borrow['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($borrow['status'] === 'pending'): ?>
                                <button class="action-btn approve-btn borrow-action-trigger" 
                                        data-id="<?= $borrow['borrow_id'] ?>" 
                                        data-user="<?= $borrow['user_id'] ?>" 
                                        data-title="<?= htmlspecialchars($borrow['title']) ?>" 
                                        data-status="approve"> Approve </button>
                                        
                                <button class="action-btn reject-btn borrow-action-trigger" 
                                        data-id="<?= $borrow['borrow_id'] ?>" 
                                        data-user="<?= $borrow['user_id'] ?>" 
                                        data-title="<?= htmlspecialchars($borrow['title']) ?>" 
                                        data-status="reject"> Reject </button>
                            <?php else: ?>
                                <span class="completed">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center">
                        No borrow records found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Intercept operational click routines
    $('.borrow-action-trigger').on('click', function() {
        let btn = $(this);
        let borrowId = btn.data('id');
        let targetUserId = btn.data('user');
        let bookTitle = btn.data('title');
        let actionType = btn.data('status');
        
        // Translated real-time user-facing notification strings
        let customMessage = actionType === 'approve' 
            ? "The administrator has approved your rental request for the book '" + bookTitle + "'."
            : "The administrator has rejected your rental request for the book '" + bookTitle + "'.";

        let targetUrl = actionType === 'approve' 
            ? '<?= BASE_URL ?>/Public/index.php?page=approve-borrow&id=' + borrowId
            : '<?= BASE_URL ?>/Public/index.php?page=reject-borrow&id=' + borrowId;

        // Perform main operational request
        $.ajax({
            url: targetUrl,
            type: 'GET',
            success: function() {
                // Post confirmation updates back down to the target user notification tray
                $.ajax({
                    url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=send',
                    type: 'POST',
                    data: {
                        sender_id: <?= (int)($_SESSION['user']['user_id'] ?? 1) ?>,
                        receiver_id: targetUserId,
                        message: customMessage
                    },
                    success: function() {
                        alert('Action completed successfully and user has been notified.');
                        location.reload();
                    }
                });
            }
        });
    });
});
</script>