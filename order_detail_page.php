<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    die('Order ID is required.');
}

// Fetch order details
$orderQuery = $mysqli->prepare("SELECT * FROM orders WHERE id = ?");
$orderQuery->bind_param('i', $order_id);
$orderQuery->execute();
$order = $orderQuery->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch order steps
$stepsQuery = $mysqli->prepare("SELECT step_name, step_date FROM order_steps WHERE order_id = ?");
$stepsQuery->bind_param('i', $order_id);
$stepsQuery->execute();
$stepsResult = $stepsQuery->get_result();

$steps = [];
while ($row = $stepsResult->fetch_assoc()) {
    $steps[$row['step_name']] = $row['step_date'];
}

// Fetch uploaded files
$filesQuery = $mysqli->prepare("SELECT step_name, file_path FROM step_files WHERE order_id = ?");
$filesQuery->bind_param('i', $order_id);
$filesQuery->execute();
$filesResult = $filesQuery->get_result();

$files = [];
while ($row = $filesResult->fetch_assoc()) {
    $files[$row['step_name']] = $row['file_path'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $last_step = null;
    $last_step_date = null;

    // Update observation
    $observation = $_POST['observation'] ?? null;
    $updateObservation = $mysqli->prepare("UPDATE orders SET observation = ? WHERE id = ?");
    $updateObservation->bind_param('si', $observation, $order_id);
    $updateObservation->execute();

    // Update timeline steps
    $step_dates = $_POST['step_dates'] ?? [];
    foreach ($step_dates as $step_name => $step_date) {
        if (!empty($step_date)) {
            // Insert or update the step in the order_steps table
            $query = $mysqli->prepare("INSERT INTO order_steps (order_id, step_name, step_date) 
                                       VALUES (?, ?, ?)
                                       ON DUPLICATE KEY UPDATE step_date = VALUES(step_date)");
            $query->bind_param('iss', $order_id, $step_name, $step_date);
            $query->execute();

            // Track the last completed step
            if (!$last_step_date || $step_date > $last_step_date) {
                $last_step = $step_name;
                $last_step_date = $step_date;
            }
        }
    }

    // Update last_step and last_step_date in the orders table
    if ($last_step && $last_step_date) {
        $updateOrder = $mysqli->prepare("UPDATE orders SET last_step = ?, last_step_date = ? WHERE id = ?");
        $updateOrder->bind_param('ssi', $last_step, $last_step_date, $order_id);
        $updateOrder->execute();
    }

    // Handle file uploads
    foreach ($_FILES['step_files']['tmp_name'] as $step_name => $tmp_name) {
        if (is_uploaded_file($tmp_name)) {
            $file_ext = pathinfo($_FILES['step_files']['name'][$step_name], PATHINFO_EXTENSION);

            // Create directory for the order if it doesn't exist
            $order_dir = "uploads/order_$order_id";
            if (!is_dir($order_dir)) {
                mkdir($order_dir, 0755, true);
            }

            // Generate file name: stepname_orderid.extension
            $file_name = "{$step_name}_{$order_id}.$file_ext";
            $file_path = "$order_dir/$file_name";

            // Move uploaded file
            if (move_uploaded_file($tmp_name, $file_path)) {
                $fileQuery = $mysqli->prepare("INSERT INTO step_files (order_id, step_name, file_path) 
                                               VALUES (?, ?, ?)
                                               ON DUPLICATE KEY UPDATE file_path = VALUES(file_path)");
                $fileQuery->bind_param('iss', $order_id, $step_name, $file_path);
                $fileQuery->execute();
            }
        }
    }

    header("Location: order_detail_page.php?id=$order_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Timeline Styles */
        .timeline {
            position: relative;
            padding: 20px 0;
            list-style: none;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 25px;
            bottom: 0;
            width: 4px;
            background: #e5e5e5;
        }

        .timeline li {
            position: relative;
            margin-bottom: 30px;
            padding-left: 50px;
        }

        .timeline li:last-child {
            margin-bottom: 0;
        }

        .timeline li::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: red;
            transition: background 0.3s ease;
        }

        .timeline li.completed::before {
            background: green;
        }

        .timeline li span {
            font-weight: bold;
        }

        .custom-upload {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-upload:hover {
            background-color: #0056b3;
        }

        .custom-upload input[type="file"] {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Order Details</h1>
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <p><strong>Client Name:</strong> <?= htmlspecialchars($order['client_name']) ?></p>
            <p><strong>VIN:</strong> <?= htmlspecialchars($order['vin']) ?></p>
            <p><strong>Vehicle Type:</strong> <?= htmlspecialchars($order['vehicle_type']) ?></p>
            <p><strong>Brand:</strong> <?= htmlspecialchars($order['vehicle_brand']) ?></p>
            <p><strong>Model:</strong> <?= htmlspecialchars($order['vehicle_model']) ?></p>
        </div>

        <h2 class="text-xl font-bold text-gray-700 mb-4">Timeline</h2>
        <form method="POST" enctype="multipart/form-data">
            <ul class="timeline">
                <?php
                $step_names = ['PROFORMA', 'COMMANDE', 'VALIDATION', 'ACCUSE', 'FACTURATION', 'ARRIVAGE', 'CARTE JAUNE', 'LIVRAISON', 'DOSSIER DAIRA'];
                foreach ($step_names as $step_name):
                    $step_date = $steps[$step_name] ?? '';
                    $file_path = $files[$step_name] ?? null;
                    $is_completed = !empty($step_date);
                ?>
                <li class="<?= $is_completed ? 'completed' : '' ?>">
                    <div class="flex items-center space-x-3 mb-2">
                      
                        <span><?= htmlspecialchars($step_name) ?></span>
                    </div>
                    <input type="date" name="step_dates[<?= htmlspecialchars($step_name) ?>]" class="border rounded p-2 w-1/3" value="<?= htmlspecialchars($step_date) ?>">
                    <label class="custom-upload">
                        Upload File
                        <input type="file" name="step_files[<?= htmlspecialchars($step_name) ?>]">
                    </label>
                    <?php if ($file_path): ?>
                        <a href="<?= htmlspecialchars($file_path) ?>" target="_blank" class="text-blue-500 ml-2">View File</a>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>

            <h3 class="text-xl font-bold text-gray-700 mb-2">Observation</h3>
            <textarea name="observation" rows="5" class="border rounded p-2 w-full mb-4"><?= htmlspecialchars($order['observation'] ?? '') ?></textarea>

            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Save Changes</button>
        </form>
        <a href="index.php" class="mt-6 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Back to Dashboard</a>
    </div>
</body>
</html>
