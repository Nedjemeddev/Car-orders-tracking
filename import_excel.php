<?php
require 'vendor/autoload.php'; // Use Composer autoloader
include 'db_connect.php';
session_start();

use PhpOffice\PhpSpreadsheet\IOFactory;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                // Skip header row
                continue;
            }

            // Map columns from the Excel file
            $client_name = $row[0];
            $vin = $row[1];
            $vehicle_brand = $row[2];
            $vehicle_model = $row[3];
            $steps = array_slice($row, 4, 9); // Steps columns (PROFORMA to DOSSIER_DAIRA)
            $observation = $row[13] ?? '';

            // Insert order into `orders` table
            $stmt = $mysqli->prepare("INSERT INTO orders (client_name, vin, vehicle_brand, vehicle_model, observation) 
                                       VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $client_name, $vin, $vehicle_brand, $vehicle_model, $observation);

            if ($stmt->execute()) {
                $order_id = $mysqli->insert_id;

                // Insert steps into `order_steps` table
                $last_step = null;
                $last_step_date = null;
                $step_names = ['PROFORMA', 'COMMANDE', 'VALIDATION', 'ACCUSE', 'FACTURATION', 'ARRIVAGE', 'CARTE_JAUNE', 'LIVRAISON', 'DOSSIER_DAIRA'];

                foreach ($steps as $i => $step_date) {
                    $step_name = $step_names[$i];

                    // Check if date exists
                    if (!empty($step_date)) {
                        $stepStmt = $mysqli->prepare("INSERT INTO order_steps (order_id, step_name, step_date) VALUES (?, ?, ?)");
                        $stepStmt->bind_param('iss', $order_id, $step_name, $step_date);
                        $stepStmt->execute();

                        // Track last completed step
                        if (empty($last_step_date) || $step_date > $last_step_date) {
                            $last_step = $step_name;
                            $last_step_date = $step_date;
                        }
                    }
                }

                // Update last_step and last_step_date in the `orders` table
                $updateStmt = $mysqli->prepare("UPDATE orders SET last_step = ?, last_step_date = ? WHERE id = ?");
                $updateStmt->bind_param('ssi', $last_step, $last_step_date, $order_id);
                $updateStmt->execute();
            }
        }

        $message = 'Data imported successfully!';
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Import Excel Data</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if ($message): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="excel_file" class="block text-gray-700">Choose Excel File</label>
                    <input type="file" id="excel_file" name="excel_file" class="w-full border rounded p-2" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Upload</button>
            </form>
        </div>
    </div>
</body>
</html>
