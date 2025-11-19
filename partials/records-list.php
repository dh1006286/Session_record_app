<?php
// require_once __DIR__ . '/../data/functions.php';
$rows = records_all();

?>

<table class="table">
    <thead>
        <th>ID</th>
        <th>Title</th>
        <th>Artist</th>
        <th style="width:100px;">Price</th>
        <th>Format</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['id']) ?></td>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['artist']) ?></td>
                    <td>$<?= number_format((float)$r['price'], 2) ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button class="btn btn-sm btn-outline-success">Add to Cart</button>
                        </form>

                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-muted text-center py-4">
                    No records found.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>