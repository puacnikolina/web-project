<?php
session_start();
require_once '../db/db.php';
require_once '../service/ArtistService.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Artist ID is required.";
    header("Location: manage_artists.php");
    exit();
}

$artistService = new ArtistService($pdo);
$artist = $artistService->getById($_GET['id']);


if (!$artist) {
    $_SESSION['error'] = "Artist not found.";
    header("Location: manage_artists.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artist</title>
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

            <h2>Edit Artist: <?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?></h2><br>

            <form action="../admin/actions/edit_artist_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($artist->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                
                <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?>" required>
                
                <input type="text" name="birth_year" placeholder="Birth Year" value="<?php echo htmlspecialchars($artist->getBirthYear(), ENT_QUOTES, 'UTF-8'); ?>">
                
                <input type="text" name="death_year" placeholder="Death Year" value="<?php echo htmlspecialchars($artist->getDeathYear(), ENT_QUOTES, 'UTF-8'); ?>">
                
                <input type="text" name="nationality" placeholder="Nationality" value="<?php echo htmlspecialchars($artist->getNationality(), ENT_QUOTES, 'UTF-8'); ?>">
                
                <textarea name="biography" placeholder="Biography" rows="15" style="width: 100%; resize: vertical;"><?php echo htmlspecialchars($artist->getBiography(), ENT_QUOTES, 'UTF-8'); ?></textarea>
                
                <?php if (!empty($artist->getProfileImage())): ?>
                    <div style="margin-bottom: 15px;">
                        <p><strong>Current Image:</strong></p>
                        <img src="../<?php echo htmlspecialchars($artist->getProfileImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Artist Image" style="width: 150px; height: auto; object-fit: cover;">

                    </div>
                <?php endif; ?>
                
                <label for="profile_image" class="btn-image">Choose New Picture (optional)</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-add">Update Artist</button>
                    <a href="manage_artists.php" class="btn-add" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>