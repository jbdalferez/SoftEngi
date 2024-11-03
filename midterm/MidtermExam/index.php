<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cinema Parking Management</title>
</head>
<body>
    <h1>Cinema Parking Management System</h1>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, User!</p>

        <form action="create_spot.php" method="GET">
        <button type="submit">Add Parking Spot</button>
        </form>
        <form action="read_spots.php" method="GET">
        <button type="submit">View Parking Spot</button>
        </form><form></form>
        <form action="update_spot.php" method="GET">
        <button type="submit">Update Parking Spot</button>
        </form><form></form>
        <form action="delete_spot.php" method="GET">
        <button type="submit">Delete Parking Spot</button>
        </form><form></form>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="register.php">Register</a><br>
        <a href="login.php">Login</a>
    <?php endif; ?>
</body>
</html>
