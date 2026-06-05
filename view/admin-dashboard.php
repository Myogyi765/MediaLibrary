<?php 
if (!isset($db)) {
    $db = \App\DB\Database::getConnection();
}
// Remove the comment layout wrapper if you want to reuse your original global navbar
// require BASE_PATH . '/View/layout/header.php'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.bg-custom {
    background-color: #8e4a4a;
}
.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}
.back-btn {
    text-decoration: none;
    color: #8e4a4a;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    margin-bottom: 15px;
    transition: color 0.2s ease;
}
.back-btn:hover {
    color: #e06767;
    text-decoration: underline;
}
</style>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 bg-custom text-white min-vh-100 p-3">
            <h4 class="mb-4">Admin Panel</h4>
            <div class="nav flex-column">
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-users">👤 Users</a>
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-reservations">📚 Reservations</a>
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-payments">💳 Payments</a>
                <a class="nav-link text-white fw-bold" href="<?= BASE_URL ?>/Public/index.php?page=admin-notifications">🔔 Notifications</a>
                <a class="nav-link text-danger mt-3" href="<?= BASE_URL ?>/Public/index.php?page=logout">🚪 Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4 bg-light">
            
      

            <h2>Dashboard</h2>
            
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Users</h6>
                        <h3><?= htmlspecialchars((string)($totalUsers ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Reservations</h6>
                        <h3><?= htmlspecialchars((string)($totalReservations ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Books</h6>
                        <h3><?= htmlspecialchars((string)($totalBooks ?? 0)) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Payments</h6>
                        <h3><?= htmlspecialchars((string)($totalPayments ?? 0)) ?></h3>
                    </div>
                </div>
            </div>

     
                                           
                                         
                    </div>
                </div>
            </div> </div> </div> </div> <?php require BASE_PATH . '/View/layout/footer.php'; ?>