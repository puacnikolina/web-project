<?php
require_once '../db/db.php';
require_once '../model/Artwork.php';
require_once '../service/ArtworkService.php';

$artworkService = new ArtworkService($pdo);
$artworks = $artworkService->getAll(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <section class="hero-section">
        <h1 class="hero-title">Shop For Artwork</h1>
    </section> <br>
    <hr style="max-width: 90%; margin: 20px auto;">

    <section class="shop-grid-section">  
        <?php foreach ($artworks as $artwork): ?>
            <div class="artist-card">
                <?php if ($artwork->getSold()): ?>
                    <img src="../<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="artowork-title"><?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="artwork-price" style="color:red; font-weight:bold;">SOLD</p>
                <?php else: ?>
                    <a href="artwork_info.php?id=<?php echo $artwork->getId(); ?>" style="text-decoration: none;">
                        <img src="../<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>">
                        <p class="artowork-title"><?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="artwork-price">€<?php echo number_format($artwork->getPrice(), 2); ?></p>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>

</body>
</html>