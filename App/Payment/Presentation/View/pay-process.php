<?php

$section = 'payment';

$pageTitle = 'Pay Process';



require BASE_PATH . '/view/Layout/header.php';

?>  <style>

body {

    background: #f4f6f9;

    font-family: Arial, sans-serif;

}



.container {

    max-width: 720px;

    margin: 40px auto;

    background: #fff;

    padding: 30px;

    border-radius: 14px;

    box-shadow: 0 8px 30px rgba(0,0,0,0.08);

}



/* HEADER */

.title {

    text-align: center;

    font-size: 22px;

    font-weight: bold;

    margin-bottom: 20px;

}



/* INFO BOX */

.info-box {

    background: #f8f9fc;

    padding: 12px 15px;

    border-radius: 10px;

    margin-bottom: 10px;

    font-weight: 600;

}



/* PAYMENT TABS */

.tabs {

    display: flex;

    gap: 10px;

    margin: 20px 0;

}



.tab {

    flex: 1;

    text-align: center;

    padding: 12px;

    border-radius: 10px;

    cursor: pointer;

    font-weight: bold;

    border: 1px solid #ddd;

    background: #fff;

    transition: 0.3s;

}



.tab.active {

    background: #6e95c0;

    color: #fff;

    border-color: #5a7ca1;

}



/* QR SECTION */

.qr-box {

    text-align: center;

    padding: 20px;

    border-radius: 12px;

    background: #fafafa;

    margin-top: 10px;

}



.qr-box img {

    width: 220px;

    border-radius: 12px;

    border: 1px solid #eee;

}



/* PHONE */

.phone {

    margin-top: 10px;

    font-size: 14px;

    color: #555;

}



/* UPLOAD */

.upload-box {

    margin-top: 25px;

    padding: 20px;

    border: 2px dashed #bbb;

    border-radius: 12px;

    background: #fcfcfc;

}



input[type="file"] {

    width: 100%;

    padding: 10px;

    margin-top: 10px;

}



/* BUTTON */

.btn {

    width: 100%;

    margin-top: 15px;

    background: linear-gradient(135deg, #4b87c2, #2982b5);

    color: #fff;

    padding: 12px;

    border: none;

    border-radius: 10px;

    font-size: 15px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;

}



.btn:hover {

    opacity: 0.9;

}



/* MOBILE */

@media (max-width: 600px) {

    .container {

        margin: 20px;

        padding: 20px;

    }



    .qr-box img {

        width: 180px;

    }

}

</style>



<div class="container">



    <div class="title">💳 Pay Process</div>



    <div class="info-box">

        📚 Media: <?= htmlspecialchars($payment['title'] ?? 'Unknown') ?>

    </div>



    <div class="info-box">

        💰 Amount: $<?= number_format($payment['amount'] ?? 0, 2) ?>

    </div>



    <!-- PAYMENT METHOD TABS -->

    <div class="tabs">

        <div class="tab active" onclick="showKpay()">KPay</div>

        <div class="tab" onclick="showWave()">Wave Pay</div>

    </div>



    <!-- KPAY -->

    <div id="kpay" class="qr-box">

        <p><b>KPay QR Payment</b></p>

        <img src="<?= BASE_URL ?>/img/kpay-qr.png" alt="KPay QR">

        <div class="phone">📱 Phone: 09-123456789</div>

    </div>



    <!-- WAVE PAY -->

    <div id="wave" class="qr-box" style="display:none;">

        <p><b>WavePay QR Payment</b></p>

        <img src="<?= BASE_URL ?>/img/wave-qr.png" alt="Wave QR">

        <div class="phone">📱 Phone: 09-XXXXXXX</div>

    </div>



    <!-- UPLOAD PROOF -->

    <div class="upload-box">



        <form action="<?= BASE_URL ?>/Public/index.php?page=upload-proof" 

              method="POST" 

              enctype="multipart/form-data">



            <input type="hidden" name="borrow_id" value="<?= $payment['borrow_id'] ?>">



            <label><b>Upload Payment Screenshot</b></label>

            <input type="file" name="proof" required>



            <button class="btn" type="submit">Submit Proof</button>

        </form>



    </div>



</div>



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

</script>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>