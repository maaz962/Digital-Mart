<?php
require_once "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_POST['add_to_cart'])) {
    $pid = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['qty']);
    if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] += $qty;
    else $_SESSION['cart'][$pid] = $qty;
    echo "<div class='alert alert-success'>Item added to cart.</div>";
}

if (isset($_POST['update_cart']) && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
        $pid = (int)$pid;
        $qty = (int)$qty;
        if ($qty <= 0) unset($_SESSION['cart'][$pid]);
        else $_SESSION['cart'][$pid] = $qty;
    }
    echo "<div class='alert alert-info'>Cart updated.</div>";
}

$cart = $_SESSION['cart'];
$subtotal = 0;
?>

<h4>Your Cart</h4>

<form method="post">
<table class="table table-bordered bg-white">
  <thead>
    <tr>
      <th>Product</th><th>Price</th><th>Qty</th><th>Total</th>
    </tr>
  </thead>
  <tbody>
  <?php if (empty($cart)): ?>
    <tr><td colspan="4" class="text-center">Cart is empty.</td></tr>
  <?php else: ?>
    <?php foreach ($cart as $pid => $qty): ?>
      <?php
      $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
      $stmt->bind_param("i", $pid);
      $stmt->execute();
      $p = $stmt->get_result()->fetch_assoc();
      if (!$p) continue;
      $lineTotal = $p['price'] * $qty;
      $subtotal += $lineTotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td>PKR <?= number_format($p['price'], 2) ?></td>
        <td style="width:120px;">
          <input type="number" min="0" name="qty[<?= (int)$pid ?>]" value="<?= (int)$qty ?>" class="form-control">
        </td>
        <td>PKR <?= number_format($lineTotal, 2) ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
  </tbody>
</table>

<button class="btn btn-secondary" name="update_cart">Update Cart</button>
<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
</form>

<?php include "includes/footer.php"; ?>