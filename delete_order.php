<?php
include 'db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $stmt = $mysqli->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param('i', $order_id);
    if ($stmt->execute()) {
        header('Location: index.php?message=Order+Deleted');
    } else {
        header('Location: index.php?error=Delete+Failed');
    }
    exit;
}
?>
