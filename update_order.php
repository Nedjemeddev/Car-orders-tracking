<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $client_name = $_POST['client_name'];
    $vin = $_POST['vin'];
    $vehicle_brand = $_POST['vehicle_brand'];
    $vehicle_model = $_POST['vehicle_model'];

    $query = $mysqli->prepare("UPDATE orders SET client_name = ?, vin = ?, vehicle_brand = ?, vehicle_model = ? WHERE id = ?");
    $query->bind_param('ssssi', $client_name, $vin, $vehicle_brand, $vehicle_model, $id);

    if ($query->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "Failed to update order.";
    }
}
?>
