<?php
session_start();
require_once '../db/db.php';
require_once '../service/ArtworkService.php';

$artworkService = new ArtworkService($pdo);
$artworks = $artworkService->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artworks</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;"> 
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Manage Artworks</h2>
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
                <a href="add_artwork.php" class="btn-add">Add New Artwork</a>
            </p>
            <div class="artists-list">
            <?php if (empty($artworks)): ?>
                <p>No artworks found. Add your first artwork!</p>
            <?php else: ?>
                <h3>All Artworks (<?php echo count($artworks); ?>)</h3>
                <div style="overflow-x:auto;">
                    <table class="artist-table">
                        <thead>
                            <tr>
                                <th><span>ID</span></th>
                                <th><span>Title</span></th>
                                <th><span>Description</span></th>
                                <th><span>Price</span></th>
                                <th><span>Image</span></th>
                                <th><span>Status</span></th>
                                <th><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($artworks as $artwork): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($artwork->getId(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(mb_substr($artwork->getDescription(), 0, 40), ENT_QUOTES, 'UTF-8'); ?><?php if (mb_strlen($artwork->getDescription()) > 40) echo '...'; ?></td>
                                <td>€<?php echo htmlspecialchars(number_format($artwork->getPrice(), 2), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if (!empty($artwork->getImage())): ?>
                                        <img src="../<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Artwork Image" class="artist-table-image">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($artwork->getSold()): ?>
                                        <span class="status-inactive">SOLD</span>
                                    <?php else: ?>
                                        <span class="status-active">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="artist-actions">
                                        <a href="../admin/edit_artwork.php?id=<?php echo $artwork->getId(); ?>" class="btn-add">Edit</a><br>
                                        <a href="../admin/actions/delete_artwork.php?id=<?php echo $artwork->getId(); ?>" class="btn-add btn-delete"
                                        onclick="return confirm('Are u sure?');" >Delete</a>
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
