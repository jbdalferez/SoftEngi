<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $spot_id = $_POST['spot_id'];
    $is_occupied = $_POST['is_occupied'];

    // Check if the spot ID exists
    $check_stmt = $conn->prepare("SELECT id FROM parking_spots WHERE id = ?");
    $check_stmt->bind_param("i", $spot_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        echo "Error: Parking spot with this ID does not exist!";
    } else {
        // Proceed with updating the spot if the ID exists
        $stmt = $conn->prepare("UPDATE parking_spots SET is_occupied = ? WHERE id = ?");
        $stmt->bind_param("ii", $is_occupied, $spot_id);
        if ($stmt->execute()) {
            echo "Parking spot updated!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Parking Spot</title>
</head>
<body>
    <h2>Update Parking Spot</h2>
    <form method="POST">
        Spot ID: <input type="number" name="spot_id" required>
        Is Occupied: 
        <select name="is_occupied">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
        <button type="submit">Update Spot</button>
    </form>
    <!-- Button to another page -->
    <form action="index.php" method="GET">
        <button type="submit">Back</button>
    </form>
</body>
</html>
