<?php
require_once "config/db.php";
include "includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);
    $message = trim($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO feedback (customer_name, customer_email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();
    echo "<div class='alert alert-success'>Thanks! Your feedback has been submitted.</div>";
}
?>

<h4>Customer Feedback</h4>
<form method="post" class="bg-white p-3 rounded shadow-sm">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="customer_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="customer_email" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Message</label>
    <textarea name="message" class="form-control" rows="4" required></textarea>
  </div>
  <button class="btn btn-primary">Submit Feedback</button>
</form>

<?php include "includes/footer.php"; ?>