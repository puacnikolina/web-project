<?php
session_start();
require_once '../db/db.php';
require_once '../service/ExhibitionService.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Exhibition ID missing.";
    header('Location: manage_exhibitions.php');
    exit;
}

$exhibitionId = (int)$_GET['id'];
$exhibitionService = new ExhibitionService($pdo);
$exhibition = $exhibitionService->getById($exhibitionId);

if (!$exhibition) {
    $_SESSION['error'] = "Exhibition not found.";
    header('Location: manage_exhibitions.php');
    exit;
}


require_once '../service/ArtistService.php';
$artistService = new ArtistService($pdo);
$artists = $artistService->getAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Exhibition</title>
    <link rel="stylesheet" href="../css/admin.css" />
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

            <h2>Edit Exhibition: <?php echo htmlspecialchars($exhibition->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h2><br>

            <form action="actions/edit_exhibition_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
            
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($exhibition->getId(), ENT_QUOTES, 'UTF-8'); ?>" />

                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($exhibition->getTitle(), ENT_QUOTES, 'UTF-8'); ?>" />
                <textarea id="description" name="description" rows="15" style="width: 100%; resize: vertical;"><?php echo htmlspecialchars($exhibition->getDescription(), ENT_QUOTES, 'UTF-8'); ?></textarea>


                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($exhibition->getStartDate(), ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required value="<?php echo htmlspecialchars($exhibition->getEndDate(), ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="artist_id">Artist:</label>
                <select name="artist_id" id="artist_id" required>
                    <option value="">-- Select Artist --</option>
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?php echo htmlspecialchars($artist->getId(), ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($artist->getId() == $exhibition->getArtistId()) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>
                    <input type="checkbox" name="is_active" <?php echo $exhibition->getIsActive() ? 'checked' : ''; ?> />
                    Active
                </label><br><br>

                <label>Current Hero Image:</label><br>
                <?php if ($exhibition->getHeroImage()): ?>
                    <img src="../<?php echo htmlspecialchars($exhibition->getHeroImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Image" style="max-width:200px; display:block; margin-bottom:10px;" />
                <?php endif; ?>
                <label for="hero_image" class="btn-image">Change Hero Image (optional)</label>
                <input type="file" id="hero_image" name="hero_image" accept="image/*" /><br><br>

                <label>Current Gallery Image:</label><br>
                <?php if ($exhibition->getGalleryImage()): ?>
                    <img src="../<?php echo htmlspecialchars($exhibition->getGalleryImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Gallery Image" style="max-width:200px; display:block; margin-bottom:10px;" />
                <?php endif; ?>
                <label for="gallery_image" class="btn-image">Change Gallery Image (optional)</label>
                <input type="file" id="gallery_image" name="gallery_image" accept="image/*" /><br><br>

                <button type="submit" class="btn-add">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
