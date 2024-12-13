<?php 
session_start(); 
include 'db.php'; 
function getRepliesForMessage($message_id) {
    global $pdo; // Assuming you have a PDO connection

    $stmt = $pdo->prepare("SELECT reply, created_at FROM replies WHERE message_id = ? ORDER BY created_at DESC");
    $stmt->execute([$message_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
} 

$user_id = $_SESSION['user_id']; 
$role = $_SESSION['role']; 

if ($role == 'hr') { 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_job'])) { 
        $title = $_POST['title']; 
        $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO job_posts (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]); 
        echo "<div class='alert success'>Job posted successfully!</div>"; 
    } 

    // Fetch job posts
    $job_posts = $pdo->query("SELECT * FROM job_posts")->fetchAll(); 

    // Fetch applications
    $applications = $pdo->query("SELECT applications.*, users.username FROM applications JOIN users ON applications.user_id = users.id")->fetchAll();

    // Accept or reject application
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        $application_id = $_POST['application_id'];
        $action = $_POST['action'];

        if ($action == 'accept') {
            $stmt = $pdo->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
            $stmt->execute([$application_id]);
            echo "<div class='alert success'>Application accepted!</div>";
        } elseif ($action == 'reject') {
            $stmt = $pdo->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$application_id]);
            echo "<div class='alert success'>Application rejected!</div>";
        }
    }
    // Fetch messages for HR
    $messages = $pdo->query("SELECT * FROM messages WHERE receiver_id = $user_id")->fetchAll(); 

} else {
    // Applicant functionalities
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) { 
        $job_post_id = $_POST['job_post_id']; 
        $resume = $_FILES['resume']['name']; 
        move_uploaded_file($_FILES['resume']['tmp_name'], "uploads/" . $resume); 
        $stmt = $pdo->prepare("INSERT INTO applications (user_id, job_post_id, resume) VALUES (?, ?, ?)"); 
        $stmt->execute([$user_id, $job_post_id, $resume]); 
        echo "<div class='alert success'>Application submitted successfully!</div>"; 
    } 

    // Send message to HR
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
        $message = $_POST['message'];
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, 1, $message]); // Assuming HR user_id is 1
        echo "<div class='alert success'>Message sent successfully!</div>";
    }

    // Fetch job posts
    $job_posts = $pdo->query("SELECT * FROM job_posts")->fetchAll(); 

    // Fetch messages sent by the applicant
    $applicant_messages = $pdo->query("SELECT * FROM messages WHERE sender_id = $user_id")->fetchAll();
}
?> 

<!DOCTYPE html> 
<html> 
<head> 
    <title>Dashboard</title> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor : pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert.success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head> 
<body>
    <div class="container">
        <h1>Welcome, <?php echo $role; ?></h1>
        <?php if ($role == 'hr'): ?>
            <h2>Post a Job</h2>
            <form method="POST">
                Title: <input type="text" name="title" required><br>
                Description: <textarea name="description" required></textarea><br>
                <input type="submit" name="post_job" value="Post Job">
            </form>
            <h2>Job Posts</h2>
            <ul>
                <?php foreach ($job_posts as $job): ?>
                    <li><?php echo $job['title']; ?> - <?php echo $job['description']; ?></li>
                <?php endforeach; ?>
            </ul>
            <h2>Applications</h2>
            <ul>
                <?php foreach ($applications as $application): ?>
                    <li>
                        <?php echo $application['username']; ?> applied for job ID: <?php echo $application['job_post_id']; ?> - Status: <?php echo $application['status']; ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                            <input type="submit" name="action" value="accept">
                            <input type="submit" name="action" value="reject">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h2>Messages</h2>
<ul>
    <?php foreach ($messages as $message): ?>
        <li>
            <?php echo htmlspecialchars($message['message']); ?> - <?php echo htmlspecialchars($message['created_at']); ?>
            <br>
            <form action="reply.php" method="POST">
                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                <textarea name="reply" required></textarea>
                <button type="submit">Reply</button>
            </form>
            <ul>
                <?php
                // Fetch replies for the current message
                $replies = getRepliesForMessage($message['id']); // Implement this function to fetch replies
                foreach ($replies as $reply): ?>
                    <li><?php echo htmlspecialchars($reply['reply']); ?> - <?php echo htmlspecialchars($reply['created_at']); ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
        <?php else: ?>
            <h2>Available Job Posts</h2>
            <ul>
                <?php foreach ($job_posts as $job): ?>
                    <li>
                        <?php echo $job['title']; ?> - <?php echo $job['description']; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="job_post_id" value="<?php echo $job['id']; ?>">
                            Resume: <input type="file" name="resume" accept=".pdf" required><br>
                            <input type="submit" name="apply" value="Apply">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <h2>Send Message to HR</h2>
            <form method="POST">
                <textarea name="message" required></textarea><br>
                <input type="submit" name="send_message" value="Send Message">
            </form>

            <h2>Your Messages</h2>
            <ul>
                <?php foreach ($applicant_messages as $msg): ?>
                    <li><?php echo $msg['message']; ?> - <?php echo $msg['created_at']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="logout.php">Logout</a>
    </div>
</body> 
</html>
