<?php
// Database connection
$host = 'localhost';
$db = 'job_application_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$message = '';
$statusCode = 200;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM applicants WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        $message = "Applicant deleted successfully.";
    } else {
        $message = "Error: " . $conn->error;
        $statusCode = 400;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Applicant</title>
</head>
<body>
    <h1>Delete Applicant</h1>
    <p>Are you sure you want to delete this applicant?</p>
    <form method="POST">
        <input type="submit" value="Yes, Delete">
    </form>
    <p><?php echo $message; ?></p>
    <a href="index.php">Back to Applicants List</a>
</body>
</html>

<?php $conn->close(); ?>
