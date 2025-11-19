

<h2>Your Cart</h2>

<!-- records_in_cart comes from the index, specificly from the if(view == cart) statement
 we need it here to make sure  -->
<?php $records = $records_in_cart ?? []; ?>

<?php if (empty($records)): ?>
  <p>Your cart is empty.</p>
<?php else: ?>

  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Price</th>   
        <th>Format</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($records as $r): ?>
        <tr>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['artist']) ?></td>
                    <td>$<?= number_format((float)$r['price'], 2) ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <form method="post">
    <input type="hidden" name="action" value="checkout">
    <button class="btn btn-success">Complete Purchase</button>
  </form>

<?php endif; ?>