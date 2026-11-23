<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';
?>
<!DOCTYPE html>
<html>
<head><title>Recycle Bin</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Recycle Bin (Deleted Reservations)</h2>
        <div class="top-nav">
            <a href="index.php">&larr; Back to Customers</a>
        </div>
        
        <table>
            <tr><th>ID</th><th>Customer ID</th><th>Date</th><th>Notes</th><th>Deleted By (User ID)</th><th>Deleted At</th><th>Action</th></tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM cat_cafe_reservations WHERE deleted_at IS NOT NULL");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['reservation_id']}</td>
                        <td>{$row['customer_id']}</td>
                        <td>{$row['reservation_date']}</td>
                        <td>" . htmlspecialchars($row['notes']) . "</td>
                        <td>" . htmlspecialchars($row['deleted_by']) . "</td>
                        <td>{$row['deleted_at']}</td>
                        <td><a href='restore.php?id={$row['reservation_id']}&customer_id={$row['customer_id']}'>Restore</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>