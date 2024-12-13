<?php
// reply.php

// Include your database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = $_POST['message_id'];
    $reply = $_POST['reply'];

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO replies (message_id, reply) VALUES (?, ?)");
    $stmt->execute([$message_id, $reply]);

    // Redirect back to the page with messages
    header("Location: messages.php"); // Change to your actual messages page
    exit();
}
?>
