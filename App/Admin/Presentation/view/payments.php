<h2>Payments</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Status</th>
    </tr>

    <?php foreach ($payments as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['amount'] ?></td>
            <td><?= $p['status'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>    