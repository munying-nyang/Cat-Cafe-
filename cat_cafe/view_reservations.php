<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

$customer_id = $_GET['customer_id'];
$stmt = $pdo->prepare("SELECT full_name FROM cat_cafe_customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

// search
$search = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = trim($_GET['search']);
    // Search in notes or date for this specific customer
    $stmt = $pdo->prepare("SELECT * FROM cat_cafe_reservations WHERE customer_id = ? AND (notes LIKE ? OR reservation_date LIKE ?)");
    $stmt->execute([$customer_id, "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM cat_cafe_reservations WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
}
?>
<!DOCTYPE html>
<html>
<head><title>Reservations</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Reservations for: <?= htmlspecialchars($customer['full_name']) ?></h2>
        
        <div class="top-nav">
            <a href="index.php">&larr; Back to Customers</a> | 
            <a href="add_reservation.php?customer_id=<?= $customer_id ?>">+ Add New Reservation</a>

            <!-- SEARCH FORM -->
            <form method="GET" style="display:inline-block; margin-left: 20px;">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                <input type="text" name="search" placeholder="Search notes or date..." value="<?= htmlspecialchars($search) ?>" style="width: 200px; display:inline-block; margin:0;">
                <button type="submit" style="padding: 10px 15px;">Search</button>
                <?php if($search): ?>
                    <a href="view_reservations.php?customer_id=<?= $customer_id ?>" style="margin-left: 10px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <table>
            <tr><th>ID</th><th>Date</th><th>Guests</th><th>Notes</th><th>Date Added</th><th>Added By (User ID)</th><th>Last Updated</th><th>Actions</th></tr>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['reservation_id']}</td>
                        <td>{$row['reservation_date']}</td>
                        <td>{$row['number_of_guest']}</td>
                        <td>" . htmlspecialchars($row['notes']) . "</td>
                        <td>{$row['date_added']}</td>
                        <td>" . htmlspecialchars($row['added_by']) . "</td>
                        <td>{$row['last_updated']}</td>
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