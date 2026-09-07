<?php
require_once "config/db.php";
include "includes/header.php";

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<div class='alert alert-warning'>Your cart is empty.</div>";
    include "includes/footer.php";
    exit;
}

$subtotal = 0;
foreach ($cart as $pid => $qty) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id=?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $p = $stmt->get_result()->fetch_assoc();
    if ($p) $subtotal += $p['price'] * $qty;
}
$shipping = ($subtotal >= 3000) ? 0 : 200;
$total = $subtotal + $shipping;
?>

<h4>Checkout</h4>
<form action="place-order.php" method="post" class="row g-3 bg-white p-3 rounded shadow-sm">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="customer_name" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="customer_email" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Payment</label>
    <select name="payment_method" class="form-select" required>
      <option value="cash_on_delivery">Cash on Delivery</option>
      <option value="card">Card</option>
      <option value="bank_transfer">Bank Transfer</option>
    </select>
  </div>
  <div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="3" required></textarea>
  </div>

  <div class="col-12">
    <div class="alert alert-info mb-0">
      Subtotal: <strong>PKR <?= number_format($subtotal, 2) ?></strong> |
      Shipping: <strong>PKR <?= number_format($shipping, 2) ?></strong> |
      Total: <strong>PKR <?= number_format($total, 2) ?></strong>
    </div>
  </div>

  <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
  <input type="hidden" name="shipping" value="<?= $shipping ?>">
  <input type="hidden" name="total" value="<?= $total ?>">

  <div class="col-12">
    <button class="btn btn-success">Place Order</button>
  </div>
</form>

<?php include "includes/footer.php"; ?>