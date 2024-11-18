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

$message = '';
$statusCode = 200;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject_specialization = $_POST['subject_specialization'];

    $query = "INSERT INTO applicants (first_name, last_name, email, phone, subject_specialization) VALUES ('$first_name', '$last_name', '$email', '$phone', '$subject_specialization')";

    if ($conn->query($query) === TRUE) {
        $message = "Applicant added successfully.";
    } else {
        $message = "Error: " . $conn->error;
        $statusCode = 400;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Applicant</title>
</head>
<body>
    <h1>Add New Applicant</h1>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Phone:</label>
        <input type="text" name="phone"><br>
        <label>Subject Specialization:</label>
        <input type="text" name="subject_specialization" required><br>
        <input type="submit" value="Add Applicant">
    </form>
    <p><?php echo $message; ?></p>
    <a href="index.php">Back to Applicants List</a>
</body>
</html>

<?php $conn->close(); ?>
