<?php
$invoiceNo = 'INV-' . date('Ymd') . '-' . str_pad($payment['payment_id'], 4, '0', STR_PAD_LEFT);

$qrText = $invoiceNo;
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrText);
?>

<style>
body{
    background:#eef2f7;
    font-family:'Segoe UI',sans-serif;
    padding:30px;
}

.invoice{
    max-width:950px;
    margin:auto;
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.08);
}

.invoice-header{
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    color:#fff;
    padding:35px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.library-info h1{
    margin:0;
    font-size:32px;
}

.library-info p{
    margin:5px 0;
    opacity:.9;
}

.invoice-no{
    text-align:right;
}

.invoice-no h2{
    margin:0;
    font-size:28px;
}

.content{
    padding:35px;
}

.success-box{
    background:#f0fff5;
    border-left:5px solid #28a745;
    padding:20px;
    border-radius:10px;
    margin-bottom:25px;
}

.success-box h2{
    margin:0;
    color:#28a745;
}

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:#f8fafc;
    padding:18px;
    border-radius:12px;
}

.card h4{
    margin-top:0;
    color:#2a5298;
}

.details-table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

.details-table th{
    background:#1e3c72;
    color:white;
    padding:14px;
    text-align:left;
}

.details-table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

.total-row{
    font-size:20px;
    font-weight:bold;
    color:#28a745;
}

.qr-section{
    text-align:center;
    margin-top:30px;
}

.qr-section img{
    border:1px solid #ddd;
    padding:10px;
    border-radius:10px;
}

.status{
    display:inline-block;
    padding:8px 16px;
    border-radius:30px;
    font-weight:600;
}

.approved{
    background:#d4edda;
    color:#155724;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.notes{
    margin-top:30px;
    background:#fff8e6;
    padding:20px;
    border-radius:12px;
    border-left:5px solid #ffc107;
}

.footer{
    margin-top:35px;
    display:flex;
    justify-content:space-between;
}

.signature{
    text-align:center;
    width:250px;
}

.signature-line{
    border-top:1px solid #000;
    margin-top:60px;
    padding-top:10px;
}

.print-btn{
    margin-top:25px;
    padding:12px 24px;
    border:none;
    border-radius:10px;
    background:#1e3c72;
    color:white;
    cursor:pointer;
    font-size:15px;
}

@media print{
    .print-btn{
        display:none;
    }

    body{
        background:white;
        padding:0;
    }

    .invoice{
        box-shadow:none;
    }
}
</style>

<div class="invoice">

    <div class="invoice-header">

        <div class="library-info">
            <h1>📚  Library</h1>
            <p>Official Payment Receipt</p>
            <p>Library Management System</p>
        </div>

        <div class="invoice-no">
            <h2>INVOICE</h2>
            <p><?= $invoiceNo ?></p>
            <p><?= date('d M Y') ?></p>
        </div>

    </div>

    <div class="content">

        <div class="success-box">
            <h2>
                Thank You,
                <?= htmlspecialchars($payment['username']) ?>!
            </h2>

            <p>
                Your payment has been successfully received and recorded.
                Thank you for supporting our library services.
            </p>
        </div>

        <div class="info-grid">

            <div class="card">
                <h4>Customer Information</h4>

                <p><strong>Username:</strong>
                    <?= htmlspecialchars($payment['username']) ?>
                </p>

                <p><strong>Borrow ID:</strong>
                    #<?= $payment['borrow_id'] ?>
                </p>
            </div>

            <div class="card">
                <h4>Payment Information</h4>

                <p><strong>Payment ID:</strong>
                    #<?= $payment['payment_id'] ?>
                </p>

                <p>
                    <strong>Status:</strong>

                    <span class="status <?= $payment['status'] ?>">
                        <?= strtoupper($payment['status']) ?>
                    </span>
                </p>

                <p>
                    <strong>Date:</strong>
                    <?= date('d M Y h:i A', strtotime($payment['payment_date'])) ?>
                </p>
            </div>

        </div>

        <table class="details-table">

            <thead>
                <tr>
                    <th>Description</th>
                    <th>Borrow Reference</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Library Borrowing Fee</td>
                    <td>#<?= $payment['borrow_id'] ?></td>
                    <td>$<?= number_format($payment['amount'],2) ?></td>
                </tr>

                <tr class="total-row">
                    <td colspan="2">TOTAL PAID</td>
                    <td>$<?= number_format($payment['amount'],2) ?></td>
                </tr>

            </tbody>

        </table>

        <div class="qr-section">

            <h3>Payment Verification QR</h3>

            <img src="<?= $qrUrl ?>" alt="Invoice QR">

            <p>
                Scan this QR code to verify invoice:
                <strong><?= $invoiceNo ?></strong>
            </p>

        </div>

        <div class="notes">

            <h3>Important Notes</h3>

            <ul>
                <li>This invoice serves as an official payment receipt.</li>
                <li>Please keep this invoice for future reference.</li>
                <li>Payments are non-refundable once approved.</li>
                <li>The QR code contains invoice verification information.</li>
                <li>For support, contact the library administrator.</li>
            </ul>

        </div>

        <div class="footer">

            <div>
                <strong>Generated By:</strong><br>
                Digital Library Management System
            </div>

            <div class="signature">
                <div class="signature-line">
                    Authorized Signature
                </div>
            </div>

        </div>

        <button class="print-btn" onclick="window.print()">
            🖨 Print Invoice / Save PDF
        </button>

    </div>

</div>