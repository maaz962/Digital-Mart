<?php
require_once "config/db.php";
include "includes/header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.name LIKE CONCAT('%', ?, '%') ORDER BY p.id DESC");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
}
?>

<div class="row mb-3">
  <div class="col-md-8">
    <h3>Welcome to Digital Mart</h3>
    <p class="text-muted">Daily-use products with easy shopping experience.</p>
  </div>
  <div class="col-md-4">
    <form method="get" class="d-flex">
      <input type="text" name="q" class="form-control me-2" placeholder="Search product..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-primary">Search</button>
    </form>
  </div>
</div>

<div class="row g-3">
  <?php while($p = $products->fetch_assoc()): ?>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($p['image'] ?: 'assets/images/default.jpg') ?>" class="card-img-top" alt="product">
        <div class="card-body">
          <h6 class="card-title"><?= htmlspecialchars($p['name']) ?></h6>
          <p class="mb-1 text-muted"><?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></p>
          <p class="fw-bold text-success">PKR <?= number_format($p['price'], 2) ?></p>
          <a href="product-details.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-primary">View Details</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include "includes/footer.php"; ?>