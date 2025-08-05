<?php
session_start();
require_once '../db/db.php';
require_once '../service/ArtworkService.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Artwork ID is required.";
    header("Location: manage_artworks.php");
    exit();
}

$artworkService = new ArtworkService($pdo);
$artwork = $artworkService->getById($_GET['id']);


if (!$artwork) {
    $_SESSION['error'] = "Artwork not found.";
    header("Location: manage_artworks.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artwork</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;">
    <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <?php
            
            if (!empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</div>";
                }
                unset($_SESSION['errors']);
            }

            
            if (!empty($_SESSION['success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['success']);
            }
            ?>

            <h2>Edit Artwork: <?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h2><br>

            <form action="../admin/actions/edit_artwork_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($artwork->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                
                <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?>" required>
                
                <textarea id="description" name="description" rows="10" style="width: 100%; resize: vertical;"><?php echo htmlspecialchars($artwork->getDescription(), ENT_QUOTES, 'UTF-8'); ?></textarea>
                
                <input type="text" name="price" placeholder="Price" value="<?php echo htmlspecialchars($artwork->getPrice(), ENT_QUOTES, 'UTF-8'); ?>" required>
                
                <?php if (!empty($artwork->getImage())): ?>
                    <div style="margin-bottom: 15px;">
                        <p><strong>Current Image:</strong></p>
                        <img src="../<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Current Artwork Image" style="max-width: 200px; height: auto; margin-bottom: 10px;">
                    </div>
                <?php endif; ?>
                
                <label for="image" class="btn-image">Choose New Image (optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-add">Update Artwork</button>
                    <a href="manage_artworks.php" class="btn-add" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
