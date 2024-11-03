<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $spot_number = $_POST['spot_number'];

    // Check if the spot number already exists
    $check_stmt = $conn->prepare("SELECT id FROM parking_spots WHERE spot_number = ?");
    $check_stmt->bind_param("i", $spot_number);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "Error: Parking spot with this number already exists!";
    } else {
        // Proceed with inserting the new spot if no duplicate is found
        $stmt = $conn->prepare("INSERT INTO parking_spots (spot_number) VALUES (?)");
        $stmt->bind_param("i", $spot_number);
        if ($stmt->execute()) {
            echo "Parking spot created!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h2>Create Parking Spot</h2>
    <form method="POST">
        Spot Number: <input type="number" name="spot_number" required>
        <button type="submit">Create Spot</button>
    </form>
        <!-- Button to another page -->
        <form action="index.php" method="GET">
        <button type="submit">Back</button>
    </form>
    
</body>
</html>
