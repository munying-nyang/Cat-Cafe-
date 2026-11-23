<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

if (isset($_GET['id']) && isset($_GET['customer_id'])) {
    $id = $_GET['id'];
    $customer_id = $_GET['customer_id'];

    $stmt = $pdo->prepare("UPDATE cat_cafe_reservations SET deleted_at = NULL, deleted_by = NULL WHERE reservation_id = ?");
    $stmt->execute([$id]);
    
    header("Location: view_reservations.php?customer_id=" . $customer_id);
    exit();
}
?>