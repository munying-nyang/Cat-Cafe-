<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Munying-Nyang Cat Cafe - Customers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1>Welcome to Munying-Nyang Cat Cafe! </h1>
            <p>Manage our lovely customers and their visit reservations below.</p>
        </div>

        <h2>Customer List</h2>
        
        <div class="top-nav">
            <a href="add_customer.php">+ Add New Customer</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM cat_cafe_customers");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['customer_id']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone_number']}</td>
                        <td class='action-links'>
                            <a href='view_reservations.php?customer_id={$row['customer_id']}'>View Reservations</a> 
                            <a href='edit_customer.php?id={$row['customer_id']}'>Edit</a> 
                            <a href='delete.php?type=customer&id={$row['customer_id']}' onclick=\"return confirm('Are you sure you want to delete this customer? All their reservations will also be deleted.');\">Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        
    </div>
</body>
</html>