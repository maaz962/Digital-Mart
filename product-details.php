<?php
require_once "config/db.php";
include "includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    include "includes/footer.php";
    exit;
}
?>

<div class="row">
  <div class="col-md-5">
    <img src="<?= htmlspecialchars($product['image'] ?: 'assets/images/default.jpg') ?>" class="img-fluid rounded shadow-sm" alt="product">
  </div>
  <div class="col-md-7">
    <h3><?= htmlspecialchars($product['name']) ?></h3>
    <p class="text-muted">Category: <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    <h4 class="text-success">PKR <?= number_format($product['price'], 2) ?></h4>

    <form action="cart.php" method="post" class="mt-3">
      <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
      <div class="row g-2 align-items-center">
        <div class="col-auto">
          <input type="number" name="qty" class="form-control" min="1" value="1" required>
        </div>
        <div class="col-auto">
          <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include "includes/footer.php"; ?>