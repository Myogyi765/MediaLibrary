<style>
/* =========================
   GLOBAL UI DESIGN
========================= */

body {
    background: linear-gradient(135deg, #eef2f7, #f8fafc);
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    color: #1f2d3d;
}

/* =========================
   PAGE TITLE
========================= */

.page-title {
    text-align: center;
    margin-bottom: 35px;
    font-size: 36px;
    font-weight: 900;
    letter-spacing: 0.5px;
    color: #1f2d3d;
}

.page-title::after {
    content: "";
    display: block;
    width: 110px;
    height: 4px;
    margin: 12px auto 0;
    border-radius: 20px;
    background: linear-gradient(90deg, #ef7d7d, #ff9f9f);
}

/* =========================
   TABLE WRAPPER
========================= */

.table-container {
    max-width: 1100px;
    margin: auto;
    background: #ffffff;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    border: 1px solid #eef0f3;
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
    background: linear-gradient(135deg, #ef7d7d, #ff8f8f);
    color: white;
}

.borrow-table th {
    padding: 16px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
}

/* BODY */
.borrow-table td {
    padding: 16px;
    font-size: 14px;
    color: #232832;
    border-bottom: 1px solid #f1f1f1;
}

/* ROW EFFECT */
.borrow-table tbody tr {
    transition: all 0.25s ease;
}

.borrow-table tbody tr:hover {
    background: #fff5f5;
    transform: scale(1.01);
}

/* EVEN ROW STRIP */
.borrow-table tbody tr:nth-child(even) {
    background: #fafafa;
}

/* =========================
   STATUS BADGES (MODERN)
========================= */

.status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

/* COLORS */
.pending {
       background: linear-gradient(135deg, #e6be0e, #ffe08a);
    color:white;
    border: 1px solid #f2c94c;
}

.approved {
  background: linear-gradient(135deg, #28a745, #34d058);
  color: white;
}

.rejected {
       background: linear-gradient(135deg, #dc3545, #ff5b6b);
       color: white;
}

.returned {
    background: linear-gradient(135deg, #cfe9ff, #7cc6ff);
    color: #0b4f7a;
    border: 1px solid #2d9cdb;
}

/* =========================
   ACTION BUTTON (RETURN)
========================= */

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 14px;
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

/* =========================
   TEXT STATES
========================= */

.table-container span {
    font-weight: 500;
}

.table-container span[style*="red"] {
    font-weight: 700;
}

.table-container span[style*="gray"] {
    color: #6c757d !important;
    font-weight: 600;
}

/* =========================
   EMPTY STATE ROW
========================= */

.table-container td[colspan] {
    padding: 25px;
    font-size: 15px;
    color: #777;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {
    .page-title {
        font-size: 26px;
    }

    .borrow-table th,
    .borrow-table td {
        padding: 12px;
        font-size: 13px;
    }

    .table-container {
        padding: 15px;
    }
}
</style>

<h2 class="page-title">My Borrow Requests</h2>
<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>/Public/index.php?page=home"
       class="back-btn">
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

    <tr>
        <td><?= htmlspecialchars($borrow['borrow_id']) ?></td>

        <td><?= htmlspecialchars($borrow['title'] ?? 'Unknown') ?></td>

        <td><?= htmlspecialchars($borrow['borrow_date']) ?></td>

        <td>
            <?= $borrow['return_date']
                ? htmlspecialchars($borrow['return_date'])
                : '-' ?>
        </td>

        <td>
            <span class="status <?= htmlspecialchars($borrow['status']) ?>">
                <?= ucfirst($borrow['status']) ?>
            </span>
        </td>

        <td>

            <?php if ($borrow['status'] === 'approved'): ?>
                <a class="action-btn return-btn"
                   href="<?= BASE_URL ?>/Public/index.php?page=return-book&id=<?= $borrow['borrow_id'] ?>">
                    Return
                </a>

            <?php elseif ($borrow['status'] === 'pending'): ?>
                <span>Waiting Admin approval</span>

            <?php elseif ($borrow['status'] === 'rejected'): ?>
                <span style="color:red;">Rejected by Admin</span>

            <?php elseif ($borrow['status'] === 'returned'): ?>
                <span style="color:gray;">Completed</span>
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