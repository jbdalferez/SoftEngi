<?php
$host = 'localhost';
$db = 'job_portal';
$user = 'root'; // default XAMPP username
$pass = ''; // default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
