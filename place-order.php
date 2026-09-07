<?php
require_once "config/db.php";
session_start();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) die("Cart is empty.");

$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = $_POST['payment_method'] ?? 'cash_on_delivery';
$subtotal = (float)($_POST['subtotal'] ?? 0);
$shipping = (float)($_POST['shipping'] ?? 0);
$total = (float)($_POST['total'] ?? 0);

$stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, phone, address, subtotal, shipping, total, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssddds", $name, $email, $phone, $address, $subtotal, $shipping, $total, $payment);
$stmt->execute();
$orderId = $stmt->insert_id;

foreach ($cart as $pid => $qty) {
    $stmtP = $conn->prepare("SELECT price, stock FROM products WHERE id=?");
    $stmtP->bind_param("i", $pid);
    $stmtP->execute();
    $p = $stmtP->get_result()->fetch_assoc();
    if (!$p) continue;

    $unit = (float)$p['price'];
    $line = $unit * $qty;

    $stmtI = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, line_total) VALUES (?, ?, ?, ?, ?)");
    $stmtI->bind_param("iiidd", $orderId, $pid, $qty, $unit, $line);
    $stmtI->execute();

    $newStock = max(0, (int)$p['stock'] - (int)$qty);
    $stmtU = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
    $stmtU->bind_param("ii", $newStock, $pid);
    $stmtU->execute();
}

$subject = "Digital Mart - Order Confirmation #".$orderId;
$message = "Dear $name,\n\nYour order has been placed successfully.\nOrder ID: $orderId\nTotal: PKR ".number_format($total,2)."\n\nThank you for shopping with Digital Mart.";
$headers = "From: no-reply@digitalmart.com";
@mail($email, $subject, $message, $headers);

unset($_SESSION['cart']);
?>
<!doctype html>
<html><head><title>Order Placed</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5">
  <div class="alert alert-success">
    <h4>Order Confirmed!</h4>
    <p>Your order ID is <strong>#<?= (int)$orderId ?></strong>. Confirmation email sent (if mail is configured).</p>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
  </div>
</div>
</body></html>