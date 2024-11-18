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
$query = "SELECT * FROM applicants WHERE id = $id";
$result = $conn->query($query);
$applicant = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applicant Details</title>
</head>
<body>
    <h1>Applicant Details</h1>
    <?php if ($applicant) { ?>
        <p><strong>First Name:</strong> <?php echo $applicant['first_name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $applicant['last_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $applicant['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $applicant['phone']; ?></p>
        <p><strong>Subject Specialization:</strong> <?php echo $applicant['subject_specialization']; ?></p>
        <p><strong>Application Date:</strong> <?php echo $applicant['application_date']; ?></p>
        <p><strong>Status:</strong> <?php echo $applicant['status']; ?></p>
    <?php } else {
        echo "<p>No applicant found.</p>";
    } ?>
    <a href="index.php">Back to Applicants List</a>
</body>
</html>

<?php $conn->close(); ?>
