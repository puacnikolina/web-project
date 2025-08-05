
<?php
require_once '../db/db.php';
require_once '../service/TicketCategoryService.php';


$exhibitionId = isset($_GET['exhibition_id']) ? (int)$_GET['exhibition_id'] : null;


if (!$exhibitionId) {
    die('Invalid exhibition.');
}
$stmt = $pdo->prepare("SELECT * FROM exhibitions WHERE id = ?");
$stmt->execute([$exhibitionId]);
$exhibition = $stmt->fetch();
if (!$exhibition) {
    die('Exhibition not found.');
}

$service = new TicketCategoryService($pdo);
$categories = $service->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <section class="hero-section">
        <h1 class="hero-title">Ticket to the Exhibition</h1>
    </section>
    <section class="ticket-purchase-section">
        <form method="post" action="../actions/process_tickets_purchase.php">
            <label for="visit-date">Choose visit date:</label>
            <input type="date" id="visit-date" name="visit_date" min="2024-07-11" max="2024-08-31" required>
            <label for="category">Age Category</label>
            <select id="category" name="category_id" required>
                <option value="">Choose an option</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category->getId()) ?>">
                        <?= htmlspecialchars($category->getCategoryName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>

            <input type="hidden" name="exhibition_id" value="<?= htmlspecialchars($exhibitionId) ?>">

            <button type="submit" class="buy-btn">BUY</button>
        </form>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </section>
</body>
</html>