<?php
require_once '../db/db.php';
require_once '../service/ArtistService.php';

$artistService = new ArtistService($pdo);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$artist = null;

if ($id) {
    $artist = $artistService->getById($id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Info</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div style="display: flex;">
        <div class="artist-pfp">
            <?php if ($artist && $artist->getProfileImage()): ?>
                <img src="../<?php echo htmlspecialchars($artist->getProfileImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Artist Image" style="max-width:400px; height:500px;">
            <?php else: ?>
                <p>No image</p>
            <?php endif; ?>
        </div>
        <div style="flex: 1; padding: 40px;" class="main-content">
    <?php if ($artist): ?>
        <h2><?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?></h2>
        <br>
        <p>
            <?php echo htmlspecialchars($artist->getNationality() ?: 'Unknown', ENT_QUOTES, 'UTF-8'); ?>,
            <?php
                $birth = $artist->getBirthYear();
                $death = $artist->getDeathYear();
                echo ($birth ? $birth : 'Unknown') . ' - ' . ($death ? $death : 'Present');
            ?>
        </p>
        <br>
        <p><?php echo nl2br(htmlspecialchars($artist->getBiography(), ENT_QUOTES, 'UTF-8')); ?></p>

        <?php else: ?>
            <p>Artist not found.</p>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>