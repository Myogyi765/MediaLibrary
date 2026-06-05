<?php 
if (!isset($db)) {
    $db = \App\DB\Database::getConnection();
}
// Remove the comment layout wrapper if you want to reuse your original global navbar
// require BASE_PATH . '/View/layout/header.php'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Admin General & Branding */
    :root {
        --primary-brand: #8e4a4a;
        --primary-brand-hover: #753b3b;
        --sidebar-text: #f8f9fa;
        --bg-light-gray: #f4f6f9;
    }

    body {
        background-color: var(--bg-light-gray);
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .bg-custom {
        background-color: var(--primary-brand);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Sidebar Navigation Improvements */
    .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease-in-out;
        opacity: 0.85;
    }
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        opacity: 1;
        transform: translateX(4px);
    }
    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2) !important;
        opacity: 1;
        font-weight: 600;
    }

    /* Action Buttons */
    .back-btn {
        text-decoration: none;
        color: var(--primary-brand);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
        transition: all 0.2s ease;
    }
    .back-btn:hover {
        color: #fff;
        background-color: var(--primary-brand);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    }

    /* Custom Responsive Table Utilities */
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }
    
    /* Modernized Scrollbar Tracking */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #aaaaaa;
    }

    /* Table Component Polish */
    .card {
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .table {
        margin-bottom: 0;
    }
    .table light-th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(142, 74, 74, 0.03) !important;
    }

    /* System Status Badges */
    .badge-user {
        background-color: #eef2f7;
        color: #333;
        border: 1px solid #dadfe5;
        font-weight: 500;
    }
    .badge-global {
        background-color: #fff3cd;
        color: #856404;
        font-weight: 500;
    }
</style>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 bg-custom text-white min-vh-100 p-3 d-flex flex-column">
            <h4 class="mb-4 px-2 pt-2 text-uppercase tracking-wider opacity-75 fs-6 fw-bold">Admin Panel</h4>
            <div class="nav flex-column progress-stacked-vertical">
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-users">👤 Users</a>
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-reservations">📚 Reservations</a>
                <a class="nav-link text-white" href="<?= BASE_URL ?>/Public/index.php?page=admin-payments">💳 Payments</a>
                <a class="nav-link text-white active" href="<?= BASE_URL ?>/Public/index.php?page=admin-notifications">🔔 Notifications</a>
                <a class="nav-link text-danger mt-auto pt-3 border-top border-secondary-subtle" href="<?= BASE_URL ?>/Public/index.php?page=logout">🚪 Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            
        
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark fw-bold d-flex align-items-center gap-2">
                                📋 Notifications Audit Log
                            </h5>
                            <span class="badge bg-secondary px-2.5 py-1.5 rounded-pill fs-7 fw-semibold"> Admin View</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 align-middle">
                                    <thead class="table-dark text-secondary">
                                        <tr>
                                            <th class="ps-4 py-3 text-uppercase fs-7" style="width: 90px;">ID</th>
                                            <th class="py-3 text-uppercase fs-7" style="width: 200px;">Target User</th>
                                            <th class="py-3 text-uppercase fs-7" style="width: 240px;">Event / Title</th>
                                            <th class="py-3 text-uppercase fs-7">Message Context</th>
                                            <th class="py-3 text-uppercase fs-7" style="width: 140px;">Category</th>
                                            <th class="pe-4 py-3 text-uppercase fs-7" style="width: 200px;">Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $allLogs = [];
                                        if (isset($db)) {
                                            require_once BASE_PATH . '/App/Notification/Model/NotificationModel.php';
                                            $notifModel = new NotificationModel($db);
                                            $allLogs = $notifModel->getAll(15);
                                        }
                                        
                                        if (empty($allLogs)): 
                                        ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted bg-white">
                                                    <div class="fs-4 mb-1">📭</div>
                                                    No recent system notifications logged.
                                                </td>
                                            </tr>
                                        <?php else: foreach ($allLogs as $log): ?>
                                            <tr>
                                                <td class="ps-4 text-secondary font-monospace fw-semibold">#<?= $log['id'] ?></td>
                                                <td>
                                                    <?php if (isset($log['user_id']) && (int)$log['user_id'] !== 0 && !empty($log['username'])): ?>
                                                        <span class="badge badge-user px-2.5 py-1.5 rounded">
                                                            👤 <?= htmlspecialchars($log['username']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-global px-2.5 py-1.5 rounded">
                                                            📢 Dashboard Global
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="text-dark fw-semibold"><?= htmlspecialchars($log['title']) ?></span></td>
                                                <td><span class="text-muted text-wrap d-block text-break" style="max-width: 450px; font-size: 0.9rem;"><?= htmlspecialchars($log['message']) ?></span></td>
                                                <td>
                                                    <span class="badge px-2 py-1.5 text-uppercase rounded fs-8 fw-bold tracking-wide <?= $log['type'] === 'payment' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' ?>">
                                                        <?= htmlspecialchars($log['type']) ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-secondary" style="font-size: 0.85rem;">
                                                    <span class="d-block text-dark-emphasis fw-medium"><?= date('Y-m-d', strtotime($log['created_at'])) ?></span>
                                                    <span class="text-muted" style="font-size: 0.75rem;"><?= date('h:i A', strtotime($log['created_at'])) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> </div> </div> </div> <?php require BASE_PATH . '/View/layout/footer.php'; ?>