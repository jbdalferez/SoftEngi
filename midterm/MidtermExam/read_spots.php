<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Parking Spots</title>
</head>
<body>
    <h2>View Parking Spots</h2>
    <?php
    $result = $conn->query("SELECT * FROM parking_spots");
    while ($row = $result->fetch_assoc()) {
        echo "Spot Number: " . $row['spot_number'] . " - Occupied: " . ($row['is_occupied'] ? 'Yes' : 'No') . "<br>";
    }
    ?>

        <!-- Button to another page -->
        <form action="index.php" method="GET">
        <button type="submit">Back</button>
    </form>
</body>
</html>
