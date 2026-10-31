<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // OVERWRITES added_by with the current user's username
    $stmt = $pdo->prepare("UPDATE cat_cafe_customers SET full_name=?, email=?, phone_number=?, added_by=? WHERE customer_id=?");
    $stmt->execute([$_POST['full_name'], $_POST['email'], $_POST['phone_number'], $_SESSION['username'], $id]);
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM cat_cafe_customers WHERE customer_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head><title>Edit Customer</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Edit Customer</h2>
        <form method="POST">
            <label>Full Name:</label> <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            <label>Email:</label> <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <label>Phone Number:</label> <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required pattern="[0-9]{11}" maxlength="11">
            <button type="submit">Update Customer</button>
        </form>
        <br><a href="index.php">&larr; Back to List</a>
    </div>
</body>
</html>