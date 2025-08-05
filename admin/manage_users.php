<?php
session_start();
require_once '../db/db.php';
require_once '../service/UserService.php';

$userService = new UserService($pdo);
$users = $userService->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;"> 
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Manage Users</h2><br>
            <p style="text-align: right; margin-bottom: 15px;"></p>
            <?php
            if (!empty($_SESSION['success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['success']);
            }
            if (!empty($_SESSION['error'])) {
                echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <div class="artists-list">
            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <h3>All Users (<?php echo count($users); ?>)</h3>
                <div style="overflow-x:auto;">
                    <table class="artist-table">
                        <thead>
                            <tr>
                                <th><span>ID</span></th>
                                <th><span>Username</span></th>
                                <th><span>Email</span></th>
                                <th><span>Role</span></th>
                                <th><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->getRole(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="artist-actions">
                                        <?php if ($user->getRole() === 'user'): ?>
                                            <a href="../admin/actions/change_role_action.php?id=<?php echo $user->getId(); ?>&role=admin" class="btn-add">Promote to Admin</a>
                                        <?php else: ?>
                                            <a href="../admin/actions/change_role_action.php?id=<?php echo $user->getId(); ?>&role=user" class="btn-add btn-delete">Demote to User</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
