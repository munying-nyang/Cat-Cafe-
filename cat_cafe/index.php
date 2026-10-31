<?php 
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php'; 
?>
<!DOCTYPE html>
<html>
<head><title>Munying-Nyang Cat Cafe</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <div class="welcome-banner">
            <h1>Welcome to Munying-Nyang Cat Cafe! 🐾</h1>
            <p>Logged in as: <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong> | <a href="logout.php">Logout</a></p>
        </div>

        <h2>Customer List</h2>
        <div class="top-nav"><a href="add_customer.php">+ Add New Customer</a></div>
        <table>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Added/Updated By</th><th>Last Updated</th><th>Actions</th></tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM cat_cafe_customers");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['customer_id']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['added_by']}</td>
                        <td>{$row['last_updated']}</td>
                        <td class='action-links'>
                            <a href='view_reservations.php?customer_id={$row['customer_id']}'>View Reservations</a> 
                            <a href='edit_customer.php?id={$row['customer_id']}'>Edit</a> 
                            <a href='delete.php?type=customer&id={$row['customer_id']}' onclick=\"return confirm('Delete this customer?');\">Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>