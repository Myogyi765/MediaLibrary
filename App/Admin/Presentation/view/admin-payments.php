<style>
body{
    background: #d8d1ce;
    font-family:"Segoe UI",Tahoma,Verdana,sans-serif;
    color:#1f2d3d;
}

/* Container */
.table-container{
    max-width:1200px;
    margin:40px auto;
    background: #d0d8da;
    padding:30px;
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,.08);
}

/* Top Bar */
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.back-btn{
    padding:10px 18px;
    background:#2563eb;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}

.back-btn:hover{
    background:#1d4ed8;
}

/* Notification */
.notification{
    background:#fff3cd;
    color:#856404;
    padding:12px 18px;
    border-radius:10px;
    font-weight:600;
    border-left:5px solid #ffc107;
}

/* Title */
h2{
    text-align:center;
    font-size:30px;
    font-weight:800;
    margin-bottom:25px;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:linear-gradient(135deg,#e76a6a,#e86565);
    color:#fff;
    padding:14px;
    font-size:12px;
    text-transform:uppercase;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

tbody tr:hover{
    background:#fff5f5;
}

/* Status Labels */
.status-paid{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
    font-weight:700;
    font-size:12px;
}

.status-unpaid{
    background:#fef9c3;
    color:#92400e;
    padding:6px 12px;
    border-radius:20px;
    font-weight:700;
    font-size:12px;
}

/* Proof Image */
.proof-img{
    width:55px;
    height:55px;
    object-fit:cover;
    border-radius:10px;
    border:2px solid #ddd;
    cursor:pointer;
    transition:.2s;
}

.proof-img:hover{
    transform:scale(1.1);
}

/* Buttons */
.approve-btn{
    background:#22c55e;
    color:#fff;
    padding:6px 10px;
    border-radius:8px;
    text-decoration:none;
    font-size:12px;
    font-weight:600;
    border: none;
    cursor: pointer;
}

.approve-btn:hover{
    background:#16a34a;
}

.invoice-btn{
    background:#3b82f6;
    color:#fff;
    padding:6px 10px;
    border-radius:8px;
    text-decoration:none;
    font-size:12px;
    font-weight:600;
}

.invoice-btn:hover{
    background:#2563eb;
}

/* Lightbox Modal */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.85);
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal img{
    max-width:90%;
    max-height:90%;
    border-radius:12px;
}

.close{
    position:absolute;
    top:20px;
    right:30px;
    font-size:35px;
    color:#fff;
    cursor:pointer;
}
</style>

<div class="table-container">
    <div class="top-bar">
        <a href="<?= BASE_URL ?>/Public/index.php?page=admin-dashboard" class="back-btn">
            ← Back to Dashboard
        </a>
    </div>

    <h2>💳 Payment Management</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Book Title</th>
                <th>User</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Borrow Status</th>
                <th>Proof of Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($payments as $p): ?>
            <tr>
                <td>#<?= $p['payment_id'] ?></td>
                <td><?= htmlspecialchars($p['book_title'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['username'] ?? '-') ?></td>
                <td>$<?= number_format($p['amount'], 2) ?></td>
                <td>
                    <span class="status-<?= strtolower($p['status']) ?>">
                        <?= strtoupper($p['status']) ?>
                    </span>
                </td>
                <td><?= $p['payment_date'] ?></td>
                <td><?= htmlspecialchars($p['borrow_status'] ?? '-') ?></td>
                <td>
                    <?php if(!empty($p['proof_image'])): ?>
                        <img class="proof-img"
                             src="<?= BASE_URL ?>/uploads/<?= $p['proof_image'] ?>"
                             onclick="openModal(this.src)"
                             alt="Payment Receipt">
                    <?php else: ?>
                        No proof provided
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p['status'] === 'unpaid'): ?>
                        <button class="approve-btn payment-approve-trigger"
                                data-id="<?= $p['payment_id'] ?>"
                                data-user="<?= $p['user_id'] ?? '' ?>"
                                data-title="<?= htmlspecialchars($p['book_title'] ?? 'Book') ?>">
                           Approve
                        </button>
                    <?php else: ?>
                        <span class="text-success fw-bold">✔ Done</span>
                    <?php endif; ?>
                    <br><br>
                    <a class="invoice-btn" href="<?= BASE_URL ?>/Public/index.php?page=invoice&payment_id=<?= $p['payment_id'] ?>">
                         View Invoice
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="imgModal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img id="modalImg" alt="Enlarged Receipt Preview">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Image Modal Controller Functions
function openModal(src){
    document.getElementById("imgModal").style.display="flex";
    document.getElementById("modalImg").src=src;
}
function closeModal(){
    document.getElementById("imgModal").style.display="none";
}

$(document).ready(function() {
    // Approve Payment Event Listener
    $('.payment-approve-trigger').on('click', function() {
        let btn = $(this);
        let paymentId = btn.data('id');
        let targetUserId = btn.data('user');
        let bookTitle = btn.data('title');
        
        // Translated real-time user-facing notification string
        let customMessage = "Your payment for the book '" + bookTitle + "' has been approved by the Administrator.";

        // Process status update on the core database engine
        $.ajax({
            url: '<?= BASE_URL ?>/Public/index.php?page=approve-payment&id=' + paymentId,
            type: 'GET',
            success: function() {
                if(targetUserId) {
                    // Trigger live push warning payload to the specific user's notification list
                    $.ajax({
                        url: '<?= BASE_URL ?>/Public/index.php?page=notification_api&action=send',
                        type: 'POST',
                        data: {
                            sender_id: <?= (int)($_SESSION['user']['user_id'] ?? 1) ?>,
                            receiver_id: targetUserId,
                            message: customMessage
                        },
                        success: function() {
                            alert('Payment has been approved and notification has been delivered to the user.');
                            location.reload();
                        }
                    });
                } else {
                    location.reload();
                }
            }
        });
    });
});
</script>