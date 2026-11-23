<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';
$customer_id = $_GET['customer_id']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //image upload
    $image_path = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO cat_cafe_reservations (customer_id, reservation_date, number_of_guest, notes, image_path, added_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$customer_id, $_POST['reservation_date'], $_POST['number_of_guest'], $_POST['notes'], $image_path, $_SESSION['user_id']]);
    header("Location: view_reservations.php?customer_id=" . $customer_id);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Reservation</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Add New Reservation</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Date:</label> 
            <input type="date" name="reservation_date" required>
            
            <label>Number of Guests:</label> 
            <input type="number" name="number_of_guest" min="1" required>
            
            <label>Notes (Optional):</label> 
            <input type="text" name="notes">

            <label>Attach Image (Optional):</label>
            <input type="file" name="image" accept="image/*">
            
            <button type="submit">Save Reservation</button>
        </form>
        <br>
        <a href="view_reservations.php?customer_id=<?= $customer_id ?>">&larr; Back to Reservations</a>
    </div>
</body>
</html>