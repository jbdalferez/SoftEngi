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

$search = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = $_POST['search'];
}

$query = "SELECT * FROM applicants WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR subject_specialization LIKE '%$search%'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application System</title>
</head>
<body>
    <h1>Job Application System</h1>
    
    <form method="POST">
        <input type="text" name="search" placeholder="Search applicants..." value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <h2>Applicants List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject Specialization</th>
            <th>Application Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['subject_specialization']; ?></td>
                    <td><?php echo $row['application_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="read.php?id=<?php echo $row['id']; ?>">View</a>
                        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='9'>No applicants found</td></tr>";
        } ?>
    </table>

    <a href="create.php">Add New Applicant</a>
</body>
</html>

<?php $conn->close(); ?>
