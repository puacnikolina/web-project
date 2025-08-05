<?php
session_start();
require_once '../db/db.php';
require_once '../service/ArtistService.php';

$artistService = new ArtistService($pdo);
$artists = $artistService->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artists</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;"> 
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Manage Artists</h2>
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
            <a href="add_artist.php" class="btn-add">Add New Artist</a>
            </p>
            <div class="artists-list">
            <?php if (empty($artists)): ?>
                <p>No artists found. Add your first artist!</p>
            <?php else: ?>
                <h3>All Artists (<?php echo count($artists); ?>)</h3>
                <div style="overflow-x:auto;">
                            <table class="artist-table">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Name</span></th>
                        <th><span>Nationality</span></th>
                        <th><span>Years</span></th>
                        <th><span>Bio</span></th>
                        <th><span>Image</span></th>
                        <th><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($artists as $artist): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($artist->getId(), ENT_QUOTES, 'UTF-8'); ?></td>

                        <td><?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($artist->getNationality(), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php echo htmlspecialchars($artist->getBirthYear(), ENT_QUOTES, 'UTF-8'); ?>
                            <?php if (!empty($artist->getDeathYear())): ?>
                                - <?php echo htmlspecialchars($artist->getDeathYear(), ENT_QUOTES, 'UTF-8'); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(mb_substr($artist->getBiography(), 0, 40), 
                        ENT_QUOTES, 'UTF-8'); ?><?php if (mb_strlen($artist->getBiography()) > 40) echo '...'; ?></td>
                        <td>
                            <?php if (!empty($artist->getProfileImage())): ?>
                                <img src="../<?php echo htmlspecialchars($artist->getProfileImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Artist Image" class="artist-table-image">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="artist-actions">
                                <a href="../admin/edit_artist.php?id=<?php echo $artist->getId(); ?>" class="btn-add">Edit</a><br>
                                <a href="../admin/actions/delete_artist.php?id=<?php echo $artist->getId(); ?>" class="btn-add btn-delete"
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