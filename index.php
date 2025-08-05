
<?php
require_once 'db/db.php';
require_once 'service/TicketCategoryService.php';
require_once 'service/ExhibitionService.php';

$exhibitionService = new ExhibitionService($pdo);
$current = $exhibitionService->getCurrent();

$service = new TicketCategoryService($pdo);
$categories = $service->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ozeum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <?php include 'partials/current_exhibition.php'; ?>
    <section class="welcome-section">
        <div class="welcome-text">
            <h1 class="welcome-message">Welcome To Ozeum<br> Art And History<br> Museum</h1>
            <br>
            <p class="welcome-info">Ozeum occupies a prominent place among the<br> leading historical and cultural 
            museums due to the<br> high value of collection presented and constant<br> activity in spheres of research, 
            exhibitions and<br> cultural education. </p>
        </div>
    </section>
    <section class="visitor-section">
    <div class="visitor-image">
        <img src="images/defaults/visitors.png" alt="Gallery Wall">
    </div>
    <div class="visitor-info">
        <h2>Visitor Info</h2>

                
        <div class="info-group">
            <h4>Admission Prices</h4>

            <?php foreach ($categories as $category): ?>
                <div class="info-row">
                    <span><?= htmlspecialchars($category->getCategoryName()) ?></span>
                    <span>$<?= number_format($category->getPrice(), 2) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="info-group">
            <h4>Opening Hours</h4>
            <div class="info-row">
                <span> Mondays-Thursdays</span><span>10 a.m. – 7 p.m.</span>
            </div>
            <div class="info-row">
                <span>Fridays-Sundays</span><span> 10 a.m.– 9 p.m.</span>
            </div>
        </div>

        <a href="pages/tickets.php?exhibition_id=<?= $current->getId() ?>" id="buy-ticket">
            Buy Ticket >>>
        </a>
    </div>
</section>
    
    <section class="upcoming-section">
        <div class="section-header">
            <h2>Upcoming Exhibitions</h2>
            <a href="pages/exhibitions.php" id="info">View All Exhibitions >>></a>
        </div>
        <hr class="section-divider">
        <img src="images/defaults/upcoming_exhibitions_section.jpg" class="section-image">
    </section>
    
    

</body>
</html>