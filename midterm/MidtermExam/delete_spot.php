<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $spot_id = $_POST['spot_id'];

    $stmt = $conn->prepare("DELETE FROM parking_spots WHERE id = ?");
    $stmt->bind_param("i", $spot_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Parking spot deleted!";
        } else {
            echo "No parking spot found with that ID.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Parking Spot</title>
</head>
<body>
    <h2>Delete Parking Spot</h2>
    <form method="POST">
        Spot ID: <input type="number" name="spot_id" required>
        <button type="submit">Delete Spot</button>
    </form>
        <!-- Button to another page -->
        <form action="index.php" method="GET">
        <button type="submit">Back</button>
    </form>
</body>
</html>
