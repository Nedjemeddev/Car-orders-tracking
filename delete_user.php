<?php
include 'db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $query = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param('i', $user_id);
    $query->execute();

    header('Location: settings.php');
    exit;
}
?>
