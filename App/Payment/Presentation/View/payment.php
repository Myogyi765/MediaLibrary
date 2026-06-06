<?php 
$section = 'payment';
$pageTitle = 'Payment Details';
require BASE_PATH . '/view/Layout/header.php'; 

if(!$payment){
    $payment = [
        'title'        => 'Book Payment',
        'status'       => 'unpaid',
        'amount'       => 5.00,
        'payment_date' => null
    ];
}
$b_id = $payment['borrow_id'] ?? ($_GET['borrow_id'] ?? 0);
?>

<style>

.payment-container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    font-family: Arial;
}
.payment-title { font-size: 26px; font-weight: 800; margin-bottom: 20px; text-align: center; }
.payment-box { border: 1px solid #eee; padding: 20px; border-radius: 10px; background: #fafafa; }
.row { display: flex; justify-content: space-between; margin-bottom: 10px; }
.label { font-weight: 600; color: #555; }
.value { font-weight: 700; }
.status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
.unpaid { background: #ffe08a; color: #7a5a00; }
.paid { background: #28a745; color: #fff; }
.pay-btn { display: block; width: 100%; margin-top: 20px; padding: 12px; background: linear-gradient(135deg, #007bff, #4da3ff); color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 700; transition: 0.3s; }
.pay-btn:hover { transform: translateY(-2px); }
</style>

<div class="payment-container">
    <div class="payment-title">Payment Details</div>
    <div class="payment-box">
        <div class="row">
            <div class="label">Media Title</div>
            <div class="value"><?= htmlspecialchars($payment['title'] ?? 'Unknown') ?></div>
        </div>

        <div class="row">
            <div class="label">Amount</div>
            <div class="value">$<?= number_format($payment['amount'] ?? 0, 2) ?></div>
        </div>

        <div class="row">
            <div class="label">Status</div>
            <div class="value">
                <span class="status <?= $payment['status'] ?? 'unpaid' ?>">
                    <?= strtoupper($payment['status'] ?? 'Unpaid') ?>
                </span>
            </div>
        </div>

        <div class="row">
            <div class="label">Payment Date</div>
            <div class="value"><?= $payment['payment_date'] ?? '-' ?></div>
        </div>

        <?php if (($payment['status'] ?? 'unpaid') === 'unpaid'): ?>
            <a class="pay-btn" href="<?= BASE_URL ?>/Public/index.php?page=pay-process&borrow_id=<?= $b_id ?>">
                Pay Now
            </a>
        <?php else: ?>
            <div style="text-align:center; margin-top:20px; color:green; font-weight:700;">
                Payment Completed
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>