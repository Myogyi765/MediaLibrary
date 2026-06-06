<?php
require BASE_PATH . '/view/Layout/header.php';
?>

<?php if (empty($item) || !is_array($item)): ?>
<?php
header('Location: ' . BASE_URL . '/Public/index.php?page=catalog');
exit;
?>
<?php endif; ?>

<style>
/* (Unchanged CSS rules) */
.login-btn {
    display: inline-block;
    padding: 10px 18px;
    background: linear-gradient(135deg, #9f7777, #db8b8b);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
}
.borrow-status-btn {
    display: inline-block;
    padding: 10px 18px;
    background: linear-gradient(135deg, #4da3ff, #007bff);
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
}
.invoice-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #28b539; 
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
    transition: color 0.2s ease, text-decoration 0.2s ease;
}
.invoice-link:hover {
    color: #bd2130;
    text-decoration: underline;
}
.status-btn {
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    cursor: not-allowed;
    display: inline-block;
    font-size: 14px;
}
.status-btn.pending { 
    background: #ffc107; 
    color: #6b4f00; 
    margin-top: 10px;
    margin-bottom: 10px;
}
.status-btn.rejected { background: #dc3545; }
.status-btn.returned { background: #17a2b8; }

.pay-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #16b156, #4da3ff);
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
}
.borrow-submit-btn {
    background: #ef7d7d;
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    font-size: 15px;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    transition: all 0.2s ease-in-out;
    margin-top:10px;
}
.return-btn {
    display: inline-block;
    padding: 10px 18px;
    background: #007bff;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
}
</style>

<div class="section page">
<div class="wrapper">

<?php require BASE_PATH . '/view/partials/breadcrumbs.php'; ?>

<div class="media-container">
    <div class="media-picture">
        <img src="<?= BASE_URL . '/' . htmlspecialchars($item['img']); ?>">
    </div>

    <div class="media-details">
        <h1><?= htmlspecialchars($item['title']); ?></h1>

        <table>
            <tr><th>Category</th><td><?= htmlspecialchars($item['category']); ?></td></tr>
            <tr><th>Genre</th><td><?= htmlspecialchars($item['genre']); ?></td></tr>
            <tr><th>Format</th><td><?= htmlspecialchars($item['format']); ?></td></tr>
            <tr><th>Year</th><td><?= htmlspecialchars($item['year']); ?></td></tr>
        </table>

        <?php if (isset($_SESSION['user'])): ?>
            <?php 
            $paymentData = $borrow['payment'] ?? null;
            $paymentStatus = strtolower(trim((string)($paymentData['status'] ?? 'unpaid'))); 
            $paymentId = $paymentData['payment_id'] ?? null;
            $borrowStatus = strtolower(trim((string)($borrow['status'] ?? '')));
            ?>

            <?php if (!empty($borrow)): ?>
                <?php if ($paymentStatus === 'paid'): ?>
                    <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px; margin-bottom: 10px;">
                        <a class="return-btn" href="<?= BASE_URL ?>/Public/index.php?page=return-book&id=<?= $borrow['borrow_id'] ?>">
                            Return Book
                        </a>
                        <?php if (!empty($paymentId)): ?>
                            <a class="invoice-link" href="<?= BASE_URL ?>/Public/index.php?page=invoice&payment_id=<?= $paymentId ?>" target="_blank">
                                🧾 Download Invoice 
                            </a>
                        <?php endif; ?>
                    </div>
                <?php elseif ($paymentStatus === 'pending'): ?>
                    <button class="status-btn pending" disabled>Payment Pending Admin Verification</button>
                <?php elseif ($borrowStatus === 'pending'): ?>
                    <button class="status-btn pending" disabled>Pending Admin Approval</button>
                <?php else: ?>
                    <?php if ($paymentStatus === 'unpaid' && $borrowStatus !== 'rejected' && $borrowStatus !== 'returned'): ?>
                        <a class="pay-btn" href="<?= BASE_URL ?>/Public/index.php?page=payment&borrow_id=<?= $borrow['borrow_id'] ?>">Pay</a>
                    <?php elseif ($borrowStatus === 'rejected'): ?>
                        <button class="status-btn rejected" disabled>Rejected</button>
                    <?php elseif ($borrowStatus === 'returned'): ?>
                        <button class="status-btn returned" disabled>Returned</button>
                    <?php else: ?>
                        <a class="pay-btn" href="<?= BASE_URL ?>/Public/index.php?page=payment&borrow_id=<?= $borrow['borrow_id'] ?>">Pay Now</a>
                    <?php endif; ?>
                <?php endif; ?>

                <div style="margin-top:10px;">
                    <a href="<?= BASE_URL ?>/Public/index.php?page=my-borrows" class="borrow-status-btn">View My Borrow Status</a>
                </div>

            <?php else: ?>
                <button type="button" class="borrow-submit-btn user-borrow-trigger" 
                        data-mediaid="<?= (int)($item['media_id'] ?? 0); ?>"
                        data-title="<?= htmlspecialchars($item['title']); ?>">
                    Borrow Now
                </button>
            <?php endif; ?>

        <?php else: ?>
            <a href="<?= BASE_URL ?>/Public/index.php?page=login" class="login-btn">Please login to borrow this item</a>
        <?php endif; ?>
    </div>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.user-borrow-trigger').on('click', function() {
        let btn = $(this);
        let mediaId = btn.data('mediaid');
        let bookTitle = btn.data('title');
        let username = '<?= htmlspecialchars($_SESSION['user']['username'] ?? 'User') ?>';

        // 1. Submit the borrow request to the backend API first
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=borrow',
            type: 'POST',
            data: { media_id: mediaId },
            success: function() {
                // 2. On success, dispatch a real-time tracking notification payload to Admin (ID: 1)
                $.ajax({
                    url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=send',
                    type: 'POST',
                    data: {
                        sender_id: <?= (int)($_SESSION['user']['user_id'] ?? 0) ?>,
                        receiver_id: 1, // Admin Account ID
                        message: "User '" + username + "' has  request for the item '" + bookTitle + "'."
                    },
                    success: function() {
                        alert('Your borrow request has been successfully submitted and the administrator has been notified.');
                        window.location.reload();
                    }
                });
            }
        });
    });
});
</script>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>