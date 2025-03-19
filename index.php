<?php
include 'db_connect.php';
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'User';

// Handle delete order request
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $deleteQuery = $mysqli->prepare("DELETE FROM orders WHERE id = ?");
    $deleteQuery->bind_param('i', $delete_id);

    if ($deleteQuery->execute()) {
        $deleteMessage = "Order #$delete_id deleted successfully.";
    } else {
        $deleteMessage = "Failed to delete order #$delete_id.";
    }
}

// Pagination and search parameters
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchTerm = $_GET['search'] ?? '';
$searchQuery = '';
if ($searchTerm) {
    $searchQuery = "WHERE client_name LIKE ? OR vin LIKE ?";
}

// Fetch total number of orders
$totalOrdersQuery = $mysqli->prepare("SELECT COUNT(*) AS total FROM orders $searchQuery");
if ($searchQuery) {
    $searchParam = "%$searchTerm%";
    $totalOrdersQuery->bind_param('ss', $searchParam, $searchParam);
}
$totalOrdersQuery->execute();
$totalOrders = $totalOrdersQuery->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalOrders / $limit);

// Fetch orders
$query = $mysqli->prepare("
    SELECT id, client_name, vin, vehicle_brand, vehicle_model, last_step, last_step_date
    FROM orders
    $searchQuery
    ORDER BY id DESC
    LIMIT ? OFFSET ?
");
if ($searchQuery) {
    $query->bind_param('ssii', $searchParam, $searchParam, $limit, $offset);
} else {
    $query->bind_param('ii', $limit, $offset);
}
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-red-500 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <img src="logo.png" alt="Logo" class="h-10 mr-2">
                <span class="text-lg font-semibold">Order Dashboard</span>
            </div>
            <!-- Navigation Links -->
            <ul class="flex space-x-6">
                <li><a href="index.php" class="hover:text-gray-300">Home</a></li>
                <li><a href="add_order.php" class="hover:text-gray-300">Add Order</a></li>
                <li><a href="settings.php" class="hover:text-gray-300">Settings</a></li>
                <li><a href="import_excel.php" class="hover:text-gray-300">Import Excel</a></li>

            </ul>
            <!-- Username and Logout -->
            <div class="flex items-center space-x-4">
                <span><?= htmlspecialchars($username) ?></span>
                <a href="logout.php" class="bg-white text-red-500 px-4 py-2 rounded hover:bg-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Orders</h1>

        <!-- Success/Error Message -->
        <?php if (isset($deleteMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <?= htmlspecialchars($deleteMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" class="mb-6">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by Client Name or VIN" 
                value="<?= htmlspecialchars($searchTerm) ?>" 
                class="w-full border rounded p-2 mb-2"
            >
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Search</button>
            <a href="index.php" class="bg-gray-300 text-black py-2 px-4 rounded hover:bg-gray-400">Reset</a>
        </form>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Client Name</th>
                        <th class="py-3 px-6 text-left">VIN</th>
                        <th class="py-3 px-6 text-left">Brand</th>
                        <th class="py-3 px-6 text-left">Model</th>
                        <th class="py-3 px-6 text-left">Last Step</th>
                        <th class="py-3 px-6 text-left">Last Step Date</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">#<?= htmlspecialchars($row['id']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['client_name']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['vin']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['vehicle_brand']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['vehicle_model']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['last_step'] ?? 'Not Set') ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['last_step_date'] ?? 'Not Set') ?></td>
                        <td class="py-3 px-6 text-center flex justify-center items-center space-x-2">
                            <!-- View Button -->
                            <a href="order_detail_page.php?id=<?= $row['id'] ?>" 
                               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" 
                               title="View Order">View</a>
                            <!-- Edit Button -->
                            <button onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['client_name']) ?>', '<?= htmlspecialchars($row['vin']) ?>', '<?= htmlspecialchars($row['vehicle_brand']) ?>', '<?= htmlspecialchars($row['vehicle_model']) ?>')" 
                                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600" 
                                    title="Edit Order">Edit</button>
                            <!-- Delete Button -->
                            <button onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['client_name']) ?>')" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" 
                                    title="Delete Order">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <span class="text-sm text-gray-600">Page <?= $page ?> of <?= $totalPages ?></span>
            <div class="flex space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">Previous</a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($searchTerm) ?>" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Edit Order</h2>
            <form id="editForm" method="POST" action="update_order.php">
                <input type="hidden" name="id" id="editOrderId">
                <div class="mb-4">
                    <label for="editClientName" class="block text-gray-700">Client Name</label>
                    <input type="text" id="editClientName" name="client_name" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="editVin" class="block text-gray-700">VIN</label>
                    <input type="text" id="editVin" name="vin" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="editVehicleBrand" class="block text-gray-700">Brand</label>
                    <select id="editVehicleBrand" name="vehicle_brand" class="w-full border rounded p-2" required>
                        <option value="FIAT">FIAT</option>
                        <option value="OPEL">OPEL</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editVehicleModel" class="block text-gray-700">Model</label>
                    <select id="editVehicleModel" name="vehicle_model" class="w-full border rounded p-2" required>
                        <option value="TIPO">TIPO</option>
                        <option value="500">500</option>
                        <option value="500X">500X</option>
                        <option value="DOBLO">DOBLO</option>
                        <option value="SCUDO">SCUDO</option>
                        <option value="DUCATO">DUCATO</option>
                        <option value="ASTRA">ASTRA</option>
                        <option value="MOKKA">MOKKA</option>
                        <option value="GRANDLAND">GRANDLAND</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-3 py-1 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Confirm Deletion</h2>
            <p id="deleteMessage" class="text-gray-600 mb-6"></p>
            <div class="flex justify-end">
                <button onclick="closeDeleteModal()" class="bg-gray-500 text-white px-3 py-1 rounded mr-2">No</button>
                <a id="confirmDeleteButton" class="bg-red-500 text-white px-3 py-1 rounded">Yes</a>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, clientName, vin, vehicleBrand, vehicleModel) {
            document.getElementById('editOrderId').value = id;
            document.getElementById('editClientName').value = clientName;
            document.getElementById('editVin').value = vin;
            document.getElementById('editVehicleBrand').value = vehicleBrand;
            document.getElementById('editVehicleModel').value = vehicleModel;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id, clientName) {
            const deleteMessage = document.getElementById('deleteMessage');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');

            deleteMessage.textContent = `Are you sure you want to delete the order for "${clientName}"?`;
            confirmDeleteButton.href = `index.php?delete_id=${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>
</html>
