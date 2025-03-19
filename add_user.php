<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate role
    $valid_roles = ['admin', 'commercial', 'adv', 'livraison'];
    if (!in_array($role, $valid_roles)) {
        die('Invalid role.');
    }

    // Insert user into database
    $query = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $query->bind_param('sss', $username, $password, $role);
    $query->execute();

    header('Location: settings.php');
    exit;
}
?>

