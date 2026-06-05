<style>
/* =========================
   GLOBAL UI DESIGN
========================= */

/* =========================
   GLOBAL UI DESIGN
========================= */

body {
    background: linear-gradient(145deg, #e6e9f0, #f8faff);
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    color: #1f2d3d;
    margin: 0;
    padding: 0;
}

/* =========================
   PAGE TITLE
========================= */

.page-title {
    text-align: center;
    margin-bottom: 40px;
    font-size: 38px;
    font-weight: 900;
    letter-spacing: 0.5px;
    color: #1f2d3d;
    position: relative;
}

.page-title::after {
    content: "";
    display: block;
    width: 120px;
    height: 5px;
    margin: 14px auto 0;
    border-radius: 25px;
    background: linear-gradient(90deg, #ff758c, #ff9f9f);
}

/* =========================
   TABLE WRAPPER
========================= */

.table-container {
    max-width: 1200px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    border: 1px solid #e0e4e8;
    overflow-x: auto;
}

/* =========================
   TABLE DESIGN
========================= */

.borrow-table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 12px;
}

/* HEADER */
.borrow-table thead {
    background: linear-gradient(135deg, #ff758c, #ff9f9f);
    color: #fff;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.borrow-table th {
    padding: 16px 12px;
    font-size: 14px;
    text-align: left;
}

/* BODY */
.borrow-table td {
    padding: 16px 12px;
    font-size: 14px;
    color: #2c3e50;
    border-bottom: 1px solid #f1f3f6;
}

/* ROW EFFECT */
.borrow-table tbody tr {
    transition: all 0.3s ease;
    cursor: default;
}

.borrow-table tbody tr:hover {
    background: #fff7f7;
    transform: translateY(-2px);
}

/* EVEN ROW STRIP */
.borrow-table tbody tr:nth-child(even) {
    background: #f9f9f9;
}

/* =========================
   STATUS BADGES
========================= */

.status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

/* STATUS COLORS */
.pending {
    background: #ffeaa7;
    color: #7a5a00;
    border: 1px solid #f2c94c;
}

.approved {
    background: #2ecc71;
    color: #fff;
}

.rejected {
    background: #e74c3c;
    color: #fff;
}

.returned {
    background: #3498db;
    color: #fff;
}

/* PAYMENT BADGES */
.payment-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    margin-left: 6px;
}

.payment-unpaid {
    background: #ffe08a;
    color: #7a5a00;
    border: 1px solid #f2c94c;
}

.payment-paid {
    background: #28a745;
    color: #fff;
}

.payment-pending {
    background: #f1f3f5;
    color: #495057;
    border: 1px solid #ced4da;
}

/* =========================
   ACTION BUTTONS
========================= */

.back-btn {
    display: inline-block;
    padding: 10px 22px;
    background: #6c5ce7;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #5a4acb;
    transform: translateY(-2px);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}

/* RETURN BUTTON */
.return-btn {
    background: linear-gradient(135deg, #007bff, #4da3ff);
    color: white;
    box-shadow: 0 4px 12px rgba(0,123,255,0.25);
}

.return-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,123,255,0.35);
}

/* PAY NOW BUTTON */
.pay-btn {
    background: linear-gradient(135deg, #ff7a00, #ffba49);
    color: white;
    box-shadow: 0 4px 12px rgba(255,123,0,0.25);
}

.pay-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(255,123,0,0.35);
}

/* =========================
   EMPTY STATE
========================= */

.table-container td[colspan] {
    padding: 25px;
    font-size: 15px;
    color: #777;
    text-align: center;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {
    .page-title {
        font-size: 28px;
    }

    .borrow-table th,
    .borrow-table td {
        padding: 10px 8px;
        font-size: 12px;
    }

    .table-container {
        padding: 20px;
    }
}
</style>

<h2 class="page-title">My Borrow Requests</h2>
<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>/Public/index.php?page=home" class="back-btn">
        ← Back to Dashboard
    </a>
</div>

<div class="table-container">
    <table class="borrow-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Media</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php if (!empty($borrows)): ?>
            <?php foreach ($borrows as $borrow): ?>

                <?php
                $borrowId = $borrow['borrow_id'] ?? '-';
                $title = $borrow['title'] ?? 'Unknown';
                $borrowDate = $borrow['borrow_date'] ?? '-';
                $returnDate = $borrow['return_date'] ?? '-';
                $status = strtolower(trim((string)($borrow['status'] ?? $borrow['borrow_status'] ?? 'unknown')));
                $paymentStatus = strtolower(trim((string)($borrow['payment']['status'] ?? 'unknown')));
                ?>

                <tr>
                    <td><?= htmlspecialchars($borrowId) ?></td>
                    <td><?= htmlspecialchars($title) ?></td>
                    <td><?= htmlspecialchars($borrowDate) ?></td>
                    <td><?= $returnDate !== '-' ? htmlspecialchars($returnDate) : '-' ?></td>
                    <td>
                        <span class="status <?= htmlspecialchars($status) ?>">
                            <?= ucfirst($status) ?>
                        </span>
                        <?php if ($paymentStatus !== 'unknown'): ?>
                            <span class="payment-badge payment-<?= htmlspecialchars($paymentStatus) ?>">
                                <?= ucfirst($paymentStatus) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($status === 'approved' && $paymentStatus !== 'paid'): ?>
                            <a class="action-btn pay-btn" href="<?= BASE_URL ?>/Public/index.php?page=payment&borrow_id=<?= $borrowId ?>">Pay Now</a>

                        <?php elseif ($status === 'approved' && $paymentStatus === 'paid'): ?>
                            <a class="action-btn return-btn" href="<?= BASE_URL ?>/Public/index.php?page=return-book&id=<?= $borrowId ?>">Return</a>

                        <?php elseif ($status === 'pending'): ?>
                            <span class="status pending">Waiting Admin approval</span>

                        <?php elseif ($status === 'rejected'): ?>
                            <span class="status rejected">Rejected by Admin</span>

                        <?php elseif ($status === 'returned'): ?>
                            <span class="status returned">Completed</span>

                        <?php else: ?>
                            <span style="color:#999;">No Action</span>
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">
                    No borrow records found.
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>