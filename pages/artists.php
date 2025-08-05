
<?php
require_once '../db/db.php';
require_once '../model/Artist.php';
require_once '../service/ArtistService.php';

$artistService = new ArtistService($pdo);
$artists = $artistService->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <section class="hero-section">
        <h1 class="hero-title">Artists</h1>
    </section>
    <hr style="max-width: 90%; margin: 40px auto;">
    <section class="artist-grid-section">  
        <?php foreach ($artists as $artist): ?>
            <div class="artist-card">
                <a href="artist_info.php?id=<?php echo $artist->getId(); ?>" style="text-decoration: none;">
                    <img src="../<?php echo htmlspecialchars($artist->getProfileImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="artist-name"><?php echo htmlspecialchars($artist->getName(), ENT_QUOTES, 'UTF-8'); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </section>

</body>
</html>