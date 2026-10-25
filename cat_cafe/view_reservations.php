<?php
require 'db.php';
$customer_id = $_GET['customer_id'];
$stmt = $pdo->prepare("SELECT full_name FROM cat_cafe_customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Reservations for: <?= htmlspecialchars($customer['full_name']) ?> </h2>
        <div class="top-nav">
            <a href="index.php">&larr; Back to Customers</a> | 
            <a href="add_reservation.php?customer_id=<?= $customer_id ?>">+ Add New Reservation</a>
        </div>
        <table>
            <tr><th>ID</th><th>Date</th><th>Guests</th><th>Notes</th><th>Actions</th></tr>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM cat_cafe_reservations WHERE customer_id = ?");
            $stmt->execute([$customer_id]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['reservation_id']}</td>
                        <td>{$row['reservation_date']}</td>
                        <td>{$row['number_of_guest']}</td>
                        <td>{$row['notes']}</td>
                        <td class='action-links'>
                            <a href='edit_reservation.php?id={$row['reservation_id']}'>Edit</a> 
                            <a href='delete.php?type=reservation&id={$row['reservation_id']}&customer_id={$customer_id}' onclick=\"return confirm('Are you sure you want to delete this reservation?');\">Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>