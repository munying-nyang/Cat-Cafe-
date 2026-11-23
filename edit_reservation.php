<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get the current image path first
    $stmt = $pdo->prepare("SELECT image_path FROM cat_cafe_reservations WHERE reservation_id = ?");
    $stmt->execute([$id]);
    $current_data = $stmt->fetch();
    $image_path = $current_data['image_path']; // Keep the old image by default

    // 2. Check if a NEW image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file; // Update to the new image
        }
    }

    // 3. Update the database
    $stmt = $pdo->prepare("UPDATE cat_cafe_reservations SET reservation_date=?, number_of_guest=?, notes=?, image_path=?, added_by=? WHERE reservation_id=?");
    $stmt->execute([$_POST['reservation_date'], $_POST['number_of_guest'], $_POST['notes'], $image_path, $_SESSION['user_id'], $id]);
    
    $stmt = $pdo->prepare("SELECT customer_id FROM cat_cafe_reservations WHERE reservation_id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    header("Location: view_reservations.php?customer_id=" . $res['customer_id']);
    exit();
}

// Fetch current reservation details for the form
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
        <!-- MUST ADD enctype for file uploads -->
        <form method="POST" enctype="multipart/form-data">
            <label>Date:</label> <input type="date" name="reservation_date" value="<?= htmlspecialchars($res['reservation_date']) ?>" required>
            <label>Guests:</label> <input type="number" name="number_of_guest" value="<?= htmlspecialchars($res['number_of_guest']) ?>" min="1" required>
            <label>Notes:</label> <input type="text" name="notes" value="<?= htmlspecialchars($res['notes']) ?>">
            
            <label>Current Image:</label><br>
            <?php if($res['image_path']): ?>
                <img src="<?= htmlspecialchars($res['image_path']) ?>" width="100" style="margin-bottom: 10px; border-radius: 5px;">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
            <br>
            
            <label>Upload New Image (Leave blank to keep current):</label>
            <input type="file" name="image" accept="image/*">
            
            <button type="submit">Update Reservation</button>
        </form>
        <br>
        <!-- Need to fetch customer_id to make the back link work -->
        <a href="view_reservations.php?customer_id=<?= $res['customer_id'] ?>">&larr; Back to Reservations</a>
    </div>
</body>
</html>