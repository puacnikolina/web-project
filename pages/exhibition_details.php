<?php
require_once '../db/db.php';
require_once '../model/Exhibition.php';
require_once '../service/ExhibitionService.php';
require_once '../service/ArtistService.php';

$exhibitionService = new ExhibitionService($pdo);
$artistService = new ArtistService($pdo);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$exhibition = null;

if ($id) {
    $exhibition = $exhibitionService->getById($id);
}

$defaultGallery = '../images/default_gallery.jpg';
$galleryImage = ($exhibition && $exhibition->getGalleryImage()) ? '../' . htmlspecialchars($exhibition->getGalleryImage()) : $defaultGallery;

$artistId = $exhibition ? $exhibition->getArtistId() : null;
$artist = $artistId ? $artistService->getById($artistId) : null;
$artistImage = $artist && $artist->getProfileImage() ? $artist->getProfileImage() : 'images/default_artist.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibition Details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <?php include '../partials/hero_exhibition.php';?>
    <section class="exhibition-detail-section">
        <div class="exhibition-detail-content">
            <?php if (isset($exhibition)) { ?>
                <img src="<?php echo $galleryImage; ?>" alt="Exhibition Gallery Image" />
                <p>
                    <?php echo nl2br(htmlspecialchars($exhibition->getDescription())); ?>
                </p>
                <?php if ($exhibition->getIsActive()): ?>
                    <a href="../pages/tickets.php?exhibition_id=<?php echo $exhibition->getId(); ?>" id="info">Buy Ticket >>></a>
                <?php else: ?>
                    
                <?php endif; ?>
                <div class="exhibition-meta-section" style="display: flex; justify-content: center; gap: 60px; margin-top: 40px;">
                    <div class="exhibition-details-col" style="min-width: 220px;">
                        <h2>Details</h2>
                        <hr />
                        <p><strong>Start:</strong><br>
                            <?php echo $exhibition->getStartDate() ? date('F j, Y', strtotime($exhibition->getStartDate())) : '-'; ?>
                        </p>
                        <p><strong>End:</strong><br>
                            <?php echo $exhibition->getEndDate() ? date('F j, Y', strtotime($exhibition->getEndDate())) : '-'; ?>
                        </p>
                </div>
                <div class="exhibition-details-col" style="min-width: 220px;">
                        <h2>Artist</h2>
                        <hr style="margin-bottom: 0px;">
                        <a href="artist_info.php?id=<?php echo $artist->getId(); ?>">
                            <img src="../<?php echo htmlspecialchars($artistImage, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($artistImage, ENT_QUOTES, 'UTF-8'); ?>" />
                        </a>
                </div>
                </div>
                
            <?php } else { ?>
                <p>No info</p>
            <?php } ?>
        </div>
    </section>
    

</body>
</html>