<?php
include 'db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'] ?? '';
    $vin = $_POST['vin'] ?? '';
    $vehicle_brand = $_POST['vehicle_brand'] ?? '';
    $vehicle_model = $_POST['vehicle_model'] ?? '';
    $current_date = date('Y-m-d'); // Automatically set the current date

    // Validate fields
    if (empty($client_name) || empty($vin) || empty($vehicle_brand) || empty($vehicle_model)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Insert the order into the `orders` table
        $query = $mysqli->prepare("INSERT INTO orders (client_name, vin, vehicle_brand, vehicle_model, last_step, last_step_date) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
        $initial_status = 'COMMANDE';
        $query->bind_param('ssssss', $client_name, $vin, $vehicle_brand, $vehicle_model, $initial_status, $current_date);

        if ($query->execute()) {
            // Insert the initial status into the `order_steps` table
            $order_id = $mysqli->insert_id; // Get the last inserted order ID
            $stepQuery = $mysqli->prepare("INSERT INTO order_steps (order_id, step_name, step_date) VALUES (?, ?, ?)");
            $stepQuery->bind_param('iss', $order_id, $initial_status, $current_date);
            $stepQuery->execute();

            $success = 'Order added successfully!';
        } else {
            $error = 'Failed to add the order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Add New Order</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label for="client_name" class="block text-gray-700">Client Name</label>
                    <input type="text" id="client_name" name="client_name" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="vin" class="block text-gray-700">VIN</label>
                    <input type="text" id="vin" name="vin" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="vehicle_brand" class="block text-gray-700">Vehicle Brand</label>
                    <select id="vehicle_brand" name="vehicle_brand" class="w-full border rounded p-2" required>
                        <option value="FIAT">FIAT</option>
                        <option value="OPEL">OPEL</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="vehicle_model" class="block text-gray-700">Vehicle Model</label>
                    <select id="vehicle_model" name="vehicle_model" class="w-full border rounded p-2" required>
                        <optgroup label="FIAT">
                            <option value="TIPO">TIPO</option>
                            <option value="500">500</option>
                            <option value="500X">500X</option>
                            <option value="DOBLO">DOBLO</option>
                            <option value="SCUDO">SCUDO</option>
                            <option value="DUCATO">DUCATO</option>
                        </optgroup>
                        <optgroup label="OPEL">
                            <option value="ASTRA">ASTRA</option>
                            <option value="MOKKA">MOKKA</option>
                            <option value="GRANDLAND">GRANDLAND</option>
                        </optgroup>
                    </select>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Order</button>
            </form>
        </div>
    </div>
</body>
</html>
