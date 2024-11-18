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
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject_specialization = $_POST['subject_specialization'];
    $status = $_POST['status'];

    $query = "UPDATE applicants SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', subject_specialization='$subject_specialization', status='$status' WHERE id=$id";

    if ($conn->query($query) === TRUE) {
        $message = "Applicant updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
        $statusCode = 400;
    }
}

$query = "SELECT * FROM applicants WHERE id = $id";
$result = $conn->query($query);
$applicant = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Applicant</title>
</head>
<body>
    <h1>Update Applicant</h1>
    <?php if ($applicant) { ?>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $applicant['first_name']; ?>" required><br>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $applicant['last_name']; ?>" required><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $applicant['email']; ?>" required><br>
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $applicant['phone']; ?>"><br>
            <label>Subject Specialization:</label>
            <input type="text" name="subject_specialization" value="<?php echo $applicant['subject_specialization']; ?>" required><br>
            <label>Status:</label>
            <select name="status">
                <option value="Pending" <?php echo ($applicant['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Interviewed" <?php echo ($applicant['status'] == 'Interviewed') ? 'selected' : ''; ?>>Interviewed</option>
                <option value="Hired" <?php echo ($applicant['status'] == 'Hired') ? 'selected' : ''; ?>>Hired</option>
                <option value="Rejected" <?php echo ($applicant['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select><br>
            <input type="submit" value="Update Applicant">
        </form>
        <p><?php echo $message; ?></p>
    <?php } else {
        echo "<p>No applicant found.</p>";
    } ?>
    <a href="index.php">Back to Applicants List</a>
</body>
</html>

<?php $conn->close(); ?>
