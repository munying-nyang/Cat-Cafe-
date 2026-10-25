<?php
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("INSERT INTO cat_cafe_customers (full_name, email, phone_number) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['full_name'], $_POST['email'], $_POST['phone_number']]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Customer</h2>
        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="full_name" required>
            
            <label>Email:</label>
            <!-- type="email" forces the browser to check for the @ symbol -->
            <input type="email" name="email" required 
                   placeholder="example@email.com"
                   title="Please enter a valid email address including the @ symbol.">
            
            <label>Phone Number:</label>
            <input type="text" name="phone_number" required 
                   pattern="[0-9]{11}" 
                   maxlength="11" 
                   minlength="11"
                   title="Please enter exactly 11 digits (numbers only)."
                   placeholder="e.g., 09123456789">
            
            <button type="submit">Save Customer</button>
        </form>
        <br>
        <a href="index.php">&larr; Back to List</a>
    </div>
</body>
</html>