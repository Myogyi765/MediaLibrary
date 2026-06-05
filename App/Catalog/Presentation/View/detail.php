<?php require BASE_PATH . '/view/Layout/header.php'; ?>

<?php if (empty($item) || !is_array($item)): ?>
    <?php
        header('Location: ' . BASE_URL . '/Public/index.php?page=catalog');
        exit;
    ?>
<?php endif; ?>

<div class="section page">
    <div class="wrapper">

        <?php require BASE_PATH . '/view/partials/breadcrumbs.php'; ?>

        <div class="media-container">

            <!-- IMAGE -->
            <div class="media-picture">
                <img src="<?= BASE_URL . '/' . htmlspecialchars($item['img']); ?>"
                     alt="<?= htmlspecialchars($item['title']); ?>">
            </div>

            <!-- DETAILS -->
            <div class="media-details">

                <h1><?= htmlspecialchars($item['title']); ?></h1>

                <table>
                    <tr>
                        <th>Category</th>
                        <td><?= htmlspecialchars($item['category']); ?></td>
                    </tr>
                    <tr>
                        <th>Genre</th>
                        <td><?= htmlspecialchars($item['genre']); ?></td>
                    </tr>
                    <tr>
                        <th>Format</th>
                        <td><?= htmlspecialchars($item['format']); ?></td>
                    </tr>
                    <tr>
                        <th>Year</th>
                        <td><?= htmlspecialchars($item['year']); ?></td>
                    </tr>
                </table>

                <!-- BORROW SECTION -->
                <?php if (isset($_SESSION['user'])): ?>

                    <?php if (!empty($borrow)): ?>

                        <?php if ($borrow['status'] === 'pending'): ?>
                            <button disabled class="status-btn pending">Pending</button>

                        <?php elseif ($borrow['status'] === 'approved'): ?>
                            <button disabled class="status-btn approved">Approved (Ready to Use)</button>

                        <?php elseif ($borrow['status'] === 'rejected'): ?>
                            <button disabled class="status-btn rejected">Rejected by Admin</button>

                        <?php elseif ($borrow['status'] === 'returned'): ?>
                            <button disabled class="status-btn returned">Returned</button>
                        <?php endif; ?>

                        <!-- OPTIONAL: link to my borrows -->
                        <div style="margin-top:10px;">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=my-borrows">
                                View
                            </a>
                        </div>

                    <?php else: ?>

                        <!-- BORROW BUTTON -->
                        <form method="post" action="<?= BASE_URL ?>/Public/index.php?page=borrow">
                            <input type="hidden" name="media_id"
                                   value="<?= (int)$item['media_id']; ?>">

                            <button type="submit" class="borrow-btn">
                                Borrow
                            </button>
                        </form>

                    <?php endif; ?>

                <?php else: ?>

                    <p>Please login to borrow this item.</p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>
