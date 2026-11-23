<?php 
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php'; 

// search
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM cat_cafe_customers WHERE full_name LIKE ? OR email LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM cat_cafe_customers");
}
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
        
        <div class="top-nav">
            <a href="add_customer.php">+ Add New Customer</a>
            
            <form method="GET" style="display:inline-block; margin-left: 20px;">
                <input type="text" name="search" placeholder="Search name or email..." value="<?= htmlspecialchars($search) ?>" style="width: 250px; display:inline-block; margin:0;">
                <button type="submit" style="padding: 10px 15px;">Search</button>
                <?php if($search): ?>
                    <a href="index.php" style="margin-left: 10px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Date Added</th><th>Added By (User ID)</th><th>Last Updated</th><th>Actions</th></tr>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['customer_id']}</td>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['phone_number']) . "</td>
                        <td>{$row['date_added']}</td>
                        <td>" . htmlspecialchars($row['added_by']) . "</td>
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