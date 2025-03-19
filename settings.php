<?php
include 'db_connect.php';
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = strtolower($_SESSION['role']); // Normalize role to lowercase

// Fetch logged-in user's data
$query = $mysqli->prepare("SELECT username, role FROM users WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $updateQuery = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateQuery->bind_param('si', $new_password, $user_id);
        $updateQuery->execute();
        $password_message = "Password updated successfully!";
    } else {
        $password_message = "Passwords do not match!";
    }
}

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_role'])) {
    $edit_user_id = $_POST['id'];
    $new_role = $_POST['role'];

    // Validate role
    $valid_roles = ['admin', 'commercial', 'adv', 'livraison'];
    if (in_array($new_role, $valid_roles)) {
        $updateRoleQuery = $mysqli->prepare("UPDATE users SET role = ? WHERE id = ?");
        $updateRoleQuery->bind_param('si', $new_role, $edit_user_id);
        $updateRoleQuery->execute();
    }
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $delete_user_id = $_POST['id'];
    $deleteQuery = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $deleteQuery->bind_param('i', $delete_user_id);
    $deleteQuery->execute();
}

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $new_role = $_POST['role'];

    // Validate role
    $valid_roles = ['admin', 'commercial', 'adv', 'livraison'];
    if (in_array($new_role, $valid_roles)) {
        $addUserQuery = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $addUserQuery->bind_param('sss', $new_username, $new_password, $new_role);
        $addUserQuery->execute();
    }
}

// Fetch all users (admin-only)
$all_users = [];
if ($role === 'admin') {
    $usersQuery = $mysqli->query("SELECT id, username, role, password FROM users");
    $all_users = $usersQuery->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-red-500 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="logo.png" alt="Logo" class="h-10 mr-2">
                <span class="text-lg font-semibold">Settings</span>
            </div>
            <ul class="flex space-x-6">
                <li><a href="index.php" class="hover:text-gray-300">Home</a></li>
                <li><a href="add_order.php" class="hover:text-gray-300">Add Order</a></li>
                <li><a href="settings.php" class="hover:text-gray-300">Settings</a></li>
            </ul>
            <div class="flex items-center space-x-4">
                <span><?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</span>
                <a href="logout.php" class="bg-white text-red-500 px-4 py-2 rounded hover:bg-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Settings</h1>

        <!-- User Info -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Your Info</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        </div>

        <!-- Change Password -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Change Password</h2>
            <?php if (isset($password_message)): ?>
                <p class="mb-4 text-green-500"><?= htmlspecialchars($password_message) ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full border rounded p-2" required>
                </div>
                <button type="submit" name="change_password" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                    Change Password
                </button>
            </form>
        </div>

        <?php if ($role === 'admin'): ?>
        <!-- Admin Panel -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">User Management</h2>

            <!-- Add User Button -->
            <button onclick="openAddUserModal()" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 mb-4">
                Add New User
            </button>

            <!-- Users Table -->
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Username</th>
                        <th class="py-3 px-6 text-left">Role</th>
                        <th class="py-3 px-6 text-left">Password</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($all_users as $user): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($user['id']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($user['role']) ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($user['password']) ?></td>
                        <td class="py-3 px-6 text-center flex justify-center items-center space-x-2">
                            <!-- Edit Role Button -->
                            <button onclick="openEditRoleModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['role']) ?>', '<?= htmlspecialchars($user['username']) ?>')" 
                                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                Edit Role
                            </button>
                            <!-- Delete User Button -->
                            <button onclick="confirmDeleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Add New User</h2>
            <form method="POST">
                <div class="mb-4">
                    <label for="addUsername" class="block text-gray-700">Username</label>
                    <input type="text" id="addUsername" name="username" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="addPassword" class="block text-gray-700">Password</label>
                    <input type="password" id="addPassword" name="password" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="addRole" class="block text-gray-700">Role</label>
                    <select id="addRole" name="role" class="w-full border rounded p-2" required>
                        <option value="admin">Admin</option>
                        <option value="commercial">Commercial</option>
                        <option value="adv">ADV</option>
                        <option value="livraison">Livraison</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeAddUserModal()" class="bg-gray-500 text-white px-3 py-1 rounded mr-2">Cancel</button>
                    <button type="submit" name="add_user" class="bg-green-500 text-white px-3 py-1 rounded">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Edit Role</h2>
            <form method="POST">
                <input type="hidden" name="id" id="editRoleUserId">
                <div class="mb-4">
                    <label for="editRoleUsername" class="block text-gray-700">Username</label>
                    <input type="text" id="editRoleUsername" class="w-full border rounded p-2 bg-gray-200" disabled>
                </div>
                <div class="mb-4">
                    <label for="editRole" class="block text-gray-700">Role</label>
                    <select id="editRole" name="role" class="w-full border rounded p-2" required>
                        <option value="admin">Admin</option>
                        <option value="commercial">Commercial</option>
                        <option value="adv">ADV</option>
                        <option value="livraison">Livraison</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditRoleModal()" class="bg-gray-500 text-white px-3 py-1 rounded mr-2">Cancel</button>
                    <button type="submit" name="edit_role" class="bg-green-500 text-white px-3 py-1 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openAddUserModal() {
            document.getElementById('addUserModal').classList.remove('hidden');
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.add('hidden');
        }

        function openEditRoleModal(id, role, username) {
            document.getElementById('editRoleUserId').value = id;
            document.getElementById('editRole').value = role;
            document.getElementById('editRoleUsername').value = username;
            document.getElementById('editRoleModal').classList.remove('hidden');
        }

        function closeEditRoleModal() {
            document.getElementById('editRoleModal').classList.add('hidden');
        }

        function confirmDeleteUser(id, username) {
            if (confirm(`Are you sure you want to delete user "${username}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="id" value="${id}"><input type="hidden" name="delete_user" value="1">`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
