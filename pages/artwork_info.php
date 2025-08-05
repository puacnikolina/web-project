<?php 
require_once '../db/db.php';
require_once '../model/Artwork.php';
require_once '../service/ArtworkService.php';

$artworkService = new ArtworkService($pdo);


$id = isset($_GET['id']) ? (int)$_GET['id'] : null;


$artwork = null;
if ($id) {
    $artwork = $artworkService->getById($id);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork Info</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <section class="hero-section">
        <h1 class="hero-title">
            <?php echo $artwork ? htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8') : 'Artwork Not Found'; ?>
        </h1>
    </section>
    <div style="display: flex;">
        <div class="artwork-img">
            <?php if ($artwork && $artwork->getImage()): ?>
                <img src="../<?php echo htmlspecialchars($artwork->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="Artist Image" style="max-width:450px; height:500px;">
            <?php else: ?>
                <p>No image</p>
            <?php endif; ?>
        </div>
        <div class="artwork-info" style="flex: 1;">
            <h1 class="artwork-title">
                <?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
            </h1>

            <p class="artwork-price">
                €<?php echo number_format($artwork->getPrice(), 2); ?>
            </p>

            <p class="artwork-description">
                <?php echo nl2br(htmlspecialchars($artwork->getDescription(), ENT_QUOTES, 'UTF-8')); ?>
            </p>

            <?php if (isset($_SESSION['cart_message'])): ?>
                <div class="cart-message" style="color: green; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_SESSION['cart_message']); ?>
                </div>
                <?php unset($_SESSION['cart_message']); ?>
            <?php endif; ?>
            <?php if ($artwork->getSold()): ?>
                <div class="sold-message" style="color:red; font-weight:bold; font-size:1.2em; margin-top:32px;">SOLD</div>
            <?php else: ?>
                <form method="post" action="../actions/add_to_cart.php" style="margin-top: 32px;">
                    <input type="hidden" name="artwork_id" value="<?= htmlspecialchars($artwork->getId()) ?>">
                    <button type="submit" class="buy-btn">ADD TO CART</button>
                </form>
            <?php endif; ?>
        </div>
    </div>


</body>
</html>
