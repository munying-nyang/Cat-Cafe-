<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("INSERT INTO cat_cafe_customers (full_name, email, phone_number, added_by) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['full_name'], $_POST['email'], $_POST['phone_number'], $_SESSION['username']]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Customer</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Add New Customer</h2>
        <form method="POST">
            <label>Full Name:</label> <input type="text" name="full_name" required>
            <label>Email:</label> <input type="email" name="email" required>
            <label>Phone Number:</label> 
            <input type="text" name="phone_number" required pattern="[0-9]{11}" maxlength="11" title="11 digits only">
            <button type="submit">Save Customer</button>
        </form>
        <br><a href="index.php">&larr; Back to List</a>
    </div>
</body>
</html>