<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($type == 'customer') {
    //permanently deleted
    $stmt = $pdo->prepare("DELETE FROM cat_cafe_customers WHERE customer_id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
} else {
    //soft delete
    $customer_id = $_GET['customer_id'];
    $stmt = $pdo->prepare("UPDATE cat_cafe_reservations SET deleted_at = CURRENT_TIMESTAMP, deleted_by = ? WHERE reservation_id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);
    header("Location: view_reservations.php?customer_id=" . $customer_id);
}
exit();
?>