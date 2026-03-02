<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch Stats
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$msgCount = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

// Fetch Users
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

// Handle Actions
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    header('Location: dashboard.php');
}

if (isset($_GET['delete_chats'])) {
    $id = $_GET['delete_chats'];
    $pdo->prepare("DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?")->execute([$id, $id]);
    header('Location: dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | WhatsApp Clone</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 40px;
            color: var(--primary-color);
        }

        .user-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .user-table th {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 5px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }
    </style>
</head>

<body style="background: #f0f2f5;">
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="btn btn-outline">Logout</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>
                    <?php echo $userCount; ?>
                </h3>
                <p>Total Registered Users</p>
            </div>
            <div class="stat-card">
                <h3>
                    <?php echo $msgCount; ?>
                </h3>
                <p>Messages Exchanged</p>
            </div>
        </div>

        <h2>Manage Users</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="../uploads/<?php echo $u['avatar']; ?>"
                                    style="width: 32px; height: 32px; border-radius: 50%;">
                                <?php echo $u['name']; ?>
                            </div>
                        </td>
                        <td>
                            <?php echo $u['email']; ?>
                        </td>
                        <td>
                            <?php if ($u['is_online']): ?>
                                <span class="badge" style="background: #d4edda; color: #155724;">Online</span>
                            <?php else: ?>
                                <span class="badge" style="background: #eee;">Offline</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                        </td>
                        <td class="actions">
                            <a href="?delete_chats=<?php echo $u['id']; ?>" class="btn btn-outline btn-sm"
                                onclick="return confirm('Clear all chats for this user?')">Clear Chats</a>
                            <a href="?delete_user=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this user permanently?')">Delete User</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
