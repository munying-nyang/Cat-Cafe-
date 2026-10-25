<?php
require 'db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("UPDATE cat_cafe_reservations SET reservation_date=?, number_of_guest=?, notes=? WHERE reservation_id=?");
    $stmt->execute([$_POST['reservation_date'], $_POST['number_of_guest'], $_POST['notes'], $id]);
    
    // Get the customer_id so we can redirect back to their specific reservation page
    $stmt = $pdo->prepare("SELECT customer_id FROM cat_cafe_reservations WHERE reservation_id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    
    header("Location: view_reservations.php?customer_id=" . $res['customer_id']);
    exit();
}

// Fetch the existing reservation data
$stmt = $pdo->prepare("SELECT * FROM cat_cafe_reservations WHERE reservation_id = ?");
$stmt->execute([$id]);
$res = $stmt->fetch();

// If reservation doesn't exist, go back
if (!$res) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Reservation</title>
    <!-- Added the CSS link here -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Added the container for consistent styling -->
    <div class="container">
        <h2>Edit Reservation</h2>
        
        <form method="POST">
            <label>Date:</label>
            <input type="date" name="reservation_date" value="<?= htmlspecialchars($res['reservation_date']) ?>" required>
            
            <label>Number of Guests:</label>
            <!-- Added min="1" so they can't enter 0 or negative numbers -->
            <input type="number" name="number_of_guest" value="<?= htmlspecialchars($res['number_of_guest']) ?>" min="1" required>
            
            <label>Notes:</label>
            <input type="text" name="notes" value="<?= htmlspecialchars($res['notes']) ?>">
            
            <button type="submit">Update Reservation</button>
        </form>
        
        <br>
        <a href="view_reservations.php?customer_id=<?= $res['customer_id'] ?>">&larr; Back to Reservations</a>
    </div>
</body>
</html>