<style>
.page-title {
   margin-bottom: 25px;
    padding-bottom: 10px;
    font-size: 28px;
    font-weight: 700;
    color: #333;
    border-bottom: 3px solid #ef7d7d;
   
    letter-spacing: 0.5px;
    text-align: center;

}
.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #3920c6;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}


.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}

.user-table thead {
    background: #ef7d7d;
    color: #fff;
}

.user-table th,
.user-table td {
    padding: 14px 16px;
    text-align: left;
}

.user-table th {
    font-weight: 600;
}

.user-table tbody tr {
    border-bottom: 1px solid #eee;
}

.user-table tbody tr:hover {
    background: #f9f9f9;
}
</style>

<h2 class="page-title">Registered Users</h2>


<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>/Public/index.php?page=admin-dashboard"
       class="back-btn">
        ← Back to Dashboard
    </a>
</div>
<div class="table-container">

    <table class="user-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                   <td><?= htmlspecialchars($user->getId()) ?></td>
        <td><?= htmlspecialchars($user->getUsername()) ?></td>
       <td><?= htmlspecialchars($user->getEmail()) ?></td>
        <td><?= htmlspecialchars($user->getRole()) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No users found.</td>
            </tr>
        <?php endif; ?>

        </tbody>

    </table>

</div>