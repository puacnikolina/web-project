<?php
require_once '../db/db.php';
require_once '../service/ExhibitionService.php';
$exhibitionService = new ExhibitionService($pdo);
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchTerm !== '') {
    $exhibitions = $exhibitionService->searchByTitle($searchTerm);
} else {
    $exhibitions = $exhibitionService->getAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibitions</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <section class="hero-section">
        <h1 class="hero-title">Exhibitions</h1>
    </section>
    <section class="exhibition-card-list">
        <form class="search-card" method="get" action="">
            <input class="search-input" type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>" />
            <button class="search-btn">FIND EVENTS</button>
        </form>
        <hr>
        <?php if ($searchTerm !== ''): ?>
            <div style="margin-bottom: 18px; color: #888;">Search results for: <strong><?php echo htmlspecialchars($searchTerm); ?></strong></div>
        <?php endif; ?>
        <?php if (empty($exhibitions)): ?>
            <p style="text-align:center; font-size:1.2rem; padding: 40px;color: #888;">No exhibitions found.</p>
        <?php else: ?>
            <?php foreach ($exhibitions as $exhibition): ?>
                <div class="exhibition-card">
                    <div class="exhibition-card-content">
                        <div class="exhibition-card-dates">
                            <?php echo date('F j, Y', strtotime($exhibition->getStartDate())); ?> - <?php echo date('F j, Y', strtotime($exhibition->getEndDate())); ?>
                        </div>
                        <div class="exhibition-card-title">
                            <a href="exhibition_details.php?id=<?php echo $exhibition->getId(); ?>">
                                <?php echo htmlspecialchars($exhibition->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </div>
                        <div class="exhibition-card-artist">
                            <?php echo htmlspecialchars($exhibition->getArtistName() ?: 'Unknown Artist', ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="exhibition-card-description">
                            <?php
                                $desc = $exhibition->getDescription();
                                $short = mb_substr($desc, 0, 222 , 'UTF-8');
                                if (mb_strlen($desc, 'UTF-8') > 40) {
                                    $short .= '...';
                                }
                                echo htmlspecialchars($short, ENT_QUOTES, 'UTF-8');
                            ?>
                        </div>

                    </div>
                    <div class="exhibition-card-image">
                        <a href="exhibition_details.php?id=<?php echo $exhibition->getId(); ?>">
                            <?php if ($exhibition->getHeroImage()): ?>
                                <img src="../<?php echo htmlspecialchars($exhibition->getHeroImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($exhibition->getTitle(), ENT_QUOTES, 'UTF-8'); ?>" />
                            <?php else: ?>
                                <img src="../images/defaults/default_hero.jpg" alt="No image" />
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</body>
</html>