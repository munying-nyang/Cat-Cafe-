<?php
require 'db.php';
$id = $_GET['id'];
$type = $_GET['type'];

if ($type == 'customer') {
    $stmt = $pdo->prepare("DELETE FROM cat_cafe_customers WHERE customer_id = ?");
    $stmt->execute([$id]);
    header("Location: index.php"); // Go back to parent list
} else {
    $customer_id = $_GET['customer_id'];
    $stmt = $pdo->prepare("DELETE FROM cat_cafe_reservations WHERE reservation_id = ?");
    $stmt->execute([$id]);
    header("Location: view_reservations.php?customer_id=" . $customer_id); // Go back to child list
}
?>