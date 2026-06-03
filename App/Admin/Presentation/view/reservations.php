<style>

    /* Page */
body {
    background: #f4f6f9;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Title */
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

/* Container */
.table-container {
    background: #fff;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    overflow-x: auto;
}

/* Table */
.borrow-table {
    width: 100%;
    border-collapse: collapse;
}

/* Header */
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

/* Body */
.borrow-table td {
    padding: 16px;
    color: #4a5568;
}

.borrow-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #edf2f7;
}

.borrow-table tbody tr:hover {
    background: #fff5f5;
    transform: scale(1.01);
}

/* Alternate Rows */
.borrow-table tbody tr:nth-child(even) {
    background: #fafafa;
}

/* Status Badge */
.status {
    display: inline-block;
    padding: 7px 15px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
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

/* Action Buttons */
.action-btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-size: 13px;
    font-weight: 600;
    transition: 0.3s ease;
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

/* Completed Status Text */
.completed {
    color: #6c757d;
    font-weight: bold;
}

/* Mobile */
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
    <a href="<?= BASE_URL ?>/Public/index.php?page=admin-dashboard"
       class="back-btn">
        ← Back to Dashboard
    </a>
</div>
<div class="table-container">

<table class="borrow-table">

<thead>
<tr>
    <th>ID</th>
    <th>User</th>
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

<td><?= htmlspecialchars($borrow['username']) ?></td>

<td><?= htmlspecialchars($borrow['title']) ?></td>

<td><?= htmlspecialchars($borrow['borrow_date']) ?></td>

<td>
    <?= $borrow['return_date']
        ? htmlspecialchars($borrow['return_date'])
        : '-' ?>
</td>

<td>

<span class="status <?= htmlspecialchars($borrow['status']) ?>">
    <?= ucfirst(htmlspecialchars($borrow['status'])) ?>
</span>

</td>

<td>

<?php if ($borrow['status'] === 'pending'): ?>

<a class="action-btn approve-btn"
   href="<?= BASE_URL ?>/Public/index.php?page=approve-borrow&id=<?= $borrow['borrow_id'] ?>">
    Approve
</a>

<a class="action-btn reject-btn"
   href="<?= BASE_URL ?>/Public/index.php?page=reject-borrow&id=<?= $borrow['borrow_id'] ?>">
    Reject
</a>

<?php else: ?>


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