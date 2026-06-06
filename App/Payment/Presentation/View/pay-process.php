<?php
$section = 'payment';
$pageTitle = 'Pay Process';
require BASE_PATH . '/view/Layout/header.php';
$b_id = $payment['borrow_id'] ?? ($_GET['borrow_id'] ?? 0);
?>
<style>
body { background: #f4f6f9; font-family: Arial, sans-serif; }
.container { max-width: 720px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 14px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
.title { text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 20px; }
.info-box { background: #f8f9fc; padding: 12px 15px; border-radius: 10px; margin-bottom: 10px; font-weight: 600; }
.tabs { display: flex; gap: 10px; margin: 20px 0; }
.tab { flex: 1; text-align: center; padding: 12px; border-radius: 10px; cursor: pointer; font-weight: bold; border: 1px solid #ddd; background: #fff; transition: 0.3s; }
.tab.active { background: #6e95c0; color: #fff; border-color: #5a7ca1; }
.qr-box { text-align: center; padding: 20px; border-radius: 12px; background: #fafafa; margin-top: 10px; }
.qr-box img { width: 220px; border-radius: 12px; border: 1px solid #eee; }
.phone { margin-top: 10px; font-size: 14px; color: #555; }
.upload-box { margin-top: 25px; padding: 20px; border: 2px dashed #bbb; border-radius: 12px; background: #fcfcfc; }
input[type="file"] { width: 100%; padding: 10px; margin-top: 10px; }
.btn { width: 100%; margin-top: 15px; background: linear-gradient(135deg, #4b87c2, #2982b5); color: #fff; padding: 12px; border: none; border-radius: 10px; font-size: 15px; font-weight: bold; cursor: pointer; transition: 0.3s; }
.btn:hover { opacity: 0.9; }

@media (max-width: 600px) {
    .container { margin: 20px; padding: 20px; }
    .qr-box img { width: 180px; }
}
</style>

<div class="container">
    <div class="title">💳 Payment Process</div>

    <div class="info-box">
        📚 Media: <?= htmlspecialchars($payment['title'] ?? 'Unknown') ?>
    </div>

    <div class="info-box">
        💰 Amount: $<?= number_format($payment['amount'] ?? 0, 2) ?>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="showKpay()">KPay</div>
        <div class="tab" onclick="showWave()">Wave Pay</div>
    </div>

    <div id="kpay" class="qr-box">
        <p><b>KPay QR Payment</b></p>
        <img src="<?= BASE_URL ?>/uploads/kpayeg.png" alt="KPay QR">
        <div class="phone">📱 Phone: 09-123456789</div>
    </div>

    <div id="wave" class="qr-box" style="display:none;">
        <p><b>WavePay QR Payment</b></p>
        <img src="<?= BASE_URL ?>/img/wave-qr.png" alt="Wave QR">
        <div class="phone">📱 Phone: 09-123456789</div>
    </div>

    <div class="upload-box">
        <form id="proofForm" enctype="multipart/form-data">
            <input type="hidden" name="borrow_id" value="<?= $b_id ?>">

            <label><b>Upload Payment Screenshot</b></label>
            <input type="file" name="proof" required>

            <button class="btn" type="submit">Submit Proof</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showKpay() {
    document.getElementById('kpay').style.display = 'block';
    document.getElementById('wave').style.display = 'none';
    document.querySelectorAll('.tab')[0].classList.add('active');
    document.querySelectorAll('.tab')[1].classList.remove('active');
}

function showWave() {
    document.getElementById('kpay').style.display = 'none';
    document.getElementById('wave').style.display = 'block';
    document.querySelectorAll('.tab')[1].classList.add('active');
    document.querySelectorAll('.tab')[0].classList.remove('active');
}

$(document).ready(function() {
    $('#proofForm').on('submit', function(e) {
        e.preventDefault(); // Prevents default form page reload behavior
        
        let formData = new FormData(this);
        let bookTitle = '<?= htmlspecialchars($payment['title'] ?? 'Book') ?>';
        let username = '<?= htmlspecialchars($_SESSION['user']['username'] ?? 'User') ?>';

        // 1. First, upload the payment image file to the server using AJAX
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=upload-proof',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function() {
                // 2. Once uploaded, the server already creates a single admin notification for proof submission.
                alert('Your payment proof has been successfully submitted. You will be notified once the administrator verifies it.');
                window.location.href = '<?= BASE_URL ?>/Public/index.php?page=my-borrows';
            }
        });
    });
});
</script>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>