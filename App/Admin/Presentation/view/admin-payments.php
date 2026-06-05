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

/* Status */
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

/* Modal */
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
<!-- 
    <?php
    $pending = 0;
    foreach ($payments as $p) {
        if (strtolower(trim((string)($p['status'] ?? ''))) === 'unpaid') {
            $pending++;
        }
    }
    ?>

    <?php if ($pending > 0): ?>
        <div class="notification">
            🔔 <?= $pending ?> Payments Waiting Approval
        </div>
    <?php endif; ?>
 -->
</div>

<h2>💳 Payment Management</h2>

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Book</th>
    <th>User</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Date</th>
    <th>Borrow</th>
    <th>Proof</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php foreach($payments as $p): ?>
<tr>

<td>#<?= $p['payment_id'] ?></td>
<td><?= htmlspecialchars($p['book_title'] ?? '-') ?></td>

<td><?= htmlspecialchars($p['username'] ?? '-') ?></td>

<td>$<?= number_format($p['amount'],2) ?></td>

<td>
    <span class="status-<?= strtolower($p['status']) ?>">
        <?= strtoupper($p['status']) ?>
    </span>
</td>

<td><?= $p['payment_date'] ?></td>

<td><?= $p['borrow_status'] ?></td>

<td>
<?php if(!empty($p['proof_image'])): ?>
    <img class="proof-img"
         src="<?= BASE_URL ?>/uploads/<?= $p['proof_image'] ?>"
         onclick="openModal(this.src)">
<?php else: ?>
    No proof
<?php endif; ?>
</td>

<td>

<?php if($p['status'] === 'unpaid'): ?>
    <a class="approve-btn"
       href="<?= BASE_URL ?>/Public/index.php?page=approve-payment&id=<?= $p['payment_id'] ?>">
       Approve
    </a>
<?php else: ?>
    ✔ Done
<?php endif; ?>

<br><br>

<a class="invoice-btn"
   href="<?= BASE_URL ?>/Public/index.php?page=invoice&payment_id=<?= $p['payment_id'] ?>">
   🧾 Invoice
</a>

</td>

</tr>
<?php endforeach; ?>

</tbody>

</table>
</div>

<!-- MODAL -->
<div id="imgModal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img id="modalImg">
</div>

<script>
function openModal(src){
    document.getElementById("imgModal").style.display="flex";
    document.getElementById("modalImg").src=src;
}
function closeModal(){
    document.getElementById("imgModal").style.display="none";
}
</script>