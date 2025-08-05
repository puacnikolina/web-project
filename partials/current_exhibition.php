<?php
require_once __DIR__ . '/../db/db.php';
require_once __DIR__ . '/../service/ExhibitionService.php';

$exhibitionService = new ExhibitionService($pdo);
$current = $exhibitionService->getCurrent();
$defaultHero = 'images/defaults/default_hero.jpg';

if ($current && !empty($current->getHeroImage())) {
    $heroImage = htmlspecialchars($current->getHeroImage(), ENT_QUOTES, 'UTF-8');
    $dates = date('F j, Y', strtotime($current->getStartDate())) . ' - ' . date('F j, Y', strtotime($current->getEndDate()));
    $artistName = htmlspecialchars($current->getArtistName() ?: 'Unknown Artist', ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($current->getTitle(), ENT_QUOTES, 'UTF-8');
} else {
    $heroImage = $defaultHero;
    $dates = '';
    $artistName = '';
    $title = '';
}
?>
<section class="hero-section-home" style="background-image: url('<?php echo $heroImage; ?>');">
    <div class="hero-info">
        <?php if ($current) { ?>
            <p id="dates"><?php echo $dates; ?></p>
            <p id="artist-name"><?php echo $artistName; ?></p>
            <br>
            <a id="info-button" href="pages/exhibition_details.php?id=<?php echo $current->getId(); ?>">READ MORE</a>
        <?php } else { ?>
            <p>No exhibition information available.</p>
        <?php } ?>
    </div>
</section>
