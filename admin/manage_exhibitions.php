<?php
session_start();
require_once '../db/db.php';
require_once '../service/ExhibitionService.php';

$exhibitionService = new ExhibitionService($pdo);
$exhibitions = $exhibitionService->getAll(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Exhibitions</title>
    <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
    <div style="display: flex;">
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Manage Exhibitions</h2>

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

            <p style="text-align: right; margin-bottom: 15px;">
                <a href="add_exhibition.php" class="btn-add">Add New Exhibition</a>
            </p>

            <div class="artists-list">
                <?php if (empty($exhibitions)): ?>
                    <p>No exhibitions found. Add your first exhibition!</p>
                <?php else: ?>
                    <h3>All Exhibitions (<?php echo count($exhibitions); ?>)</h3>
                    <div style="overflow-x:auto;">
                        <table class="artist-table">
                            <thead>
                                <tr>
                                    <th><span>ID</span></th>
                                    <th><span>Title</span></th>
                                    <th><span>Start Date</span></th>
                                    <th><span>End Date</span></th>
                                    <th><span>Status</span></th>
                                    <th><span>Artist</span></th>
                                    <th><span>Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($exhibitions as $exhibition): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($exhibition->getId(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($exhibition->getTitle(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($exhibition->getStartDate(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($exhibition->getEndDate(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $exhibition->getIsActive() ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $exhibition->getIsActive() ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo !empty($exhibition->getArtistName()) ? htmlspecialchars($exhibition->getArtistName(), ENT_QUOTES, 'UTF-8') : 'No artist'; ?></td>
                                    <td>
                                        <div class="artist-actions">
                                            <a href="../admin/edit_exhibition.php?id=<?php echo htmlspecialchars($exhibition->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="btn-add">Edit</a><br>
                                            <a href="../admin/actions/delete_exhibition.php?id=<?php echo htmlspecialchars($exhibition->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="btn-add btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
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
