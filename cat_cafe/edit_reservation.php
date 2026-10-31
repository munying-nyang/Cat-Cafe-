<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // OVERWRITES added_by with the current user's username
    $stmt = $pdo->prepare("UPDATE cat_cafe_reservations SET reservation_date=?, number_of_guest=?, notes=?, added_by=? WHERE reservation_id=?");
    $stmt->execute([$_POST['reservation_date'], $_POST['number_of_guest'], $_POST['notes'], $_SESSION['username'], $id]);
    
    $stmt = $pdo->prepare("SELECT customer_id FROM cat_cafe_reservations WHERE reservation_id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    header("Location: view_reservations.php?customer_id=" . $res['customer_id']);
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM cat_cafe_reservations WHERE reservation_id = ?");
$stmt->execute([$id]);
$res = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head><title>Edit Reservation</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Edit Reservation</h2>
        <form method="POST">
            <label>Date:</label> <input type="date" name="reservation_date" value="<?= htmlspecialchars($res['reservation_date']) ?>" required>
            <label>Guests:</label> <input type="number" name="number_of_guest" value="<?= htmlspecialchars($res['number_of_guest']) ?>" min="1" required>
            <label>Notes:</label> <input type="text" name="notes" value="<?= htmlspecialchars($res['notes']) ?>">
            <button type="submit">Update Reservation</button>
        </form>
        <br><a href="view_reservations.php?customer_id=<?= $res['customer_id'] ?>">&larr; Back to Reservations</a>
    </div>
</body>
</html>