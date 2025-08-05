<?php
session_start();
require_once __DIR__ . '/../db/db.php';
require_once __DIR__ . '/../service/ArtistService.php';


$artistService = new ArtistService($pdo);
$artists = $artistService->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exhibition</title>
    <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
    <div style="display: flex;">
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Add New Exhibition</h2><br>
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
            <form action="../admin/actions/add_exhibition_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
                <input type="text" name="title" placeholder="Title" required />
                <textarea name="description" placeholder="Description"></textarea>
                
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" required />
                
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" required />

                
                <label for="artist_id">Artist:</label>
                <select name="artist_id" id="artist_id" required>
                    <option value="">-- Select Artist --</option>
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?php echo htmlspecialchars($artist->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                
                <label for="is_active" style="margin-top: 10px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" />
                    Active Exhibition
                </label>

                <label for="hero_image" class="btn-image">Choose Hero Image</label>
                <input type="file" id="hero_image" name="hero_image" accept="image/*" required />

                <label for="gallery_image" class="btn-image">Choose Gallery Image</label>
                <input type="file" id="gallery_image" name="gallery_image" accept="image/*" required />

                <button type="submit" class="btn-add">Add Exhibition</button>
            </form>


        </div>

    </div>
</body>
</html>