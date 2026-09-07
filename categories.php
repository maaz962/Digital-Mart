<?php
require_once "config/db.php";
include "includes/header.php";

$cats = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<h4>Categories</h4>
<div class="row g-3">
<?php while($c = $cats->fetch_assoc()): ?>
  <div class="col-md-4">
    <div class="card p-3 h-100">
      <h6><?= htmlspecialchars($c['name']) ?></h6>
      <p class="text-muted mb-0"><?= htmlspecialchars($c['description']) ?></p>
    </div>
  </div>
<?php endwhile; ?>
</div>
<?php include "includes/footer.php"; ?>