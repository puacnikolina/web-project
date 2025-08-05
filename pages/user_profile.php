<?php
session_start();
require_once '../db/db.php';
require_once '../service/ArtworkService.php';
require_once '../service/OrderService.php';
require_once '../service/UserService.php';
require_once '../service/TicketService.php';
require_once '../service/ExhibitionService.php';
require_once '../service/TicketCategoryService.php';

$artworkService = new ArtworkService($pdo);
$orderService = new OrderService($pdo);
$userService = new UserService($pdo);
$ticketService = new TicketService($pdo);
$exhibitionService = new ExhibitionService($pdo);
$categoryService = new TicketCategoryService($pdo);
$cart = [];
if (isset($_SESSION['user_id'])) {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}
$user = $userService->getById($_SESSION['user_id']);
$orders = $orderService->getOrdersByUserId($_SESSION['user_id']);
$tickets = $ticketService->getTicketsByUserId($_SESSION['user_id']);
$categories = $categoryService->getAllCategories();
$username = $user ? $user->getUsername() : 'User';


$visitCount = 1;
if (isset($_COOKIE['visit_count'])) {
    $visitCount = (int)$_COOKIE['visit_count'] + 1;
}
setcookie('visit_count', $visitCount, time() + 60*60*24*365, '/'); // 1 year
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background:#faf9f3;">
    <?php include '../partials/navbar.php'; ?>
    <div class="profile-wrapper">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h2>Hello, <?php echo htmlspecialchars($username); ?></h2>
            <div style="text-align:right; color:#888; font-size:0.95em; margin-top:-18px; margin-bottom:18px;">
                You have visited this site <?php echo $visitCount; ?> times.
            </div>
        <?php endif; ?>
        <div class="profile-cards">
            
            <div class="profile-card">
                <h3>Cart</h3>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p style="color:#555;">Please log in to use the cart.</p>
                <?php elseif (empty($cart)): ?>
                    <p style="color:#555;">No products in the cart.</p>
                <?php else: ?>
                    <ul style="margin-bottom:18px;">
                        <?php 
                        $total = 0;
                        $hasSold = false;
                        foreach ($cart as $artworkId):
                            $artwork = $artworkService->getById($artworkId);
                            if ($artwork): 
                                $total += $artwork->getPrice();
                                $isSold = $artwork->getSold();
                                if ($isSold) $hasSold = true;
                        ?>
                                <li style="margin-bottom:8px;">
                                    <?php echo htmlspecialchars($artwork->getTitle()); ?> - €<?php echo number_format($artwork->getPrice(), 2); ?>
                                    <?php if ($isSold): ?>
                                        <span style="color:red; font-weight:bold;">(SOLD)</span>
                                    <?php endif; ?>
                                    <form method="post" action="../actions/remove_from_cart.php" style="display:inline; margin-left:10px;">
                                        <input type="hidden" name="artwork_id" value="<?php echo $artworkId; ?>">
                                        <button type="submit" style="color:red;background:none;border:none;cursor:pointer;">Remove</button>
                                    </form>
                                </li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                    <div class="cart-total">Total: €<?php echo number_format($total, 2); ?></div>
                    <form method="post" action="../actions/place_order.php">
                        <button type="submit" class="cart-order-button" <?php if ($hasSold) echo 'disabled title="Remove sold items from cart first"'; ?>>Order</button>
                    </form>
                    <?php if ($hasSold): ?>
                        <div class="cart-warning">Remove sold items from your cart to place an order.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="profile-card">
                <h3>Orders</h3>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p style="color:#555;">Log in to view your orders.</p>
                <?php elseif (empty($orders)): ?>
                    <p style="color:#555;">No orders yet.</p>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="profile-entry">
                            <div style="font-weight:600;">Order #<?php echo $order->getId(); ?> <span style="float:right;font-weight:400;font-size:0.95em;">Status: <?php echo htmlspecialchars($order->getStatus()); ?></span></div>
                            <div style="font-size:0.95em; color:#666; margin-bottom:4px;">Date: <?php echo $order->getOrderDate(); ?></div>
                            <div style="font-size:0.95em; color:#666; margin-bottom:4px;">Total: €<?php echo number_format($order->getTotalAmount(), 2); ?></div>
                            <div style="font-size:0.95em; color:#666;">Items:
                                <ul>
                                    <?php foreach ($order->getItems() as $item): 
                                        $artwork = $artworkService->getById($item->getArtworkId());
                                        if ($artwork):
                                    ?>
                                        <li><?php echo htmlspecialchars($artwork->getTitle()); ?> - €<?php echo number_format($item->getPricePerItem(), 2); ?></li>
                                    <?php endif; endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="profile-card">
                <h3>Tickets</h3>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p style="color:#555;">Log in to view your tickets.</p>
                <?php elseif (empty($tickets)): ?>
                    <p style="color:#555;">No tickets yet.</p>
                <?php else: ?>
                    <?php foreach ($tickets as $ticket): 
                        $exhibition = $exhibitionService->getById($ticket->getExhibitionId());
                        $categoryName = '';
                        foreach ($categories as $cat) {
                            if ($cat->getId() == $ticket->getCategoryId()) {
                                $categoryName = $cat->getCategoryName();
                                break;
                            }
                        }
                    ?>
                        <div class="profile-entry">
                            <div style="font-weight:600; margin-bottom:4px;">
                                <?php echo $exhibition ? htmlspecialchars($exhibition->getTitle()) : 'Exhibition'; ?>
                            </div>
                            <div style="font-size:0.95em; color:#666; margin-bottom:2px;">Category: <?php echo htmlspecialchars($categoryName); ?></div>
                            <div style="font-size:0.95em; color:#666; margin-bottom:2px;">Quantity: <?php echo $ticket->getQuantity(); ?></div>
                            <div style="font-size:0.95em; color:#666; margin-bottom:2px;">Total: €<?php echo number_format($ticket->getTotalPrice(), 2); ?></div>
                            <div style="font-size:0.95em; color:#666;">Reserved: <?php echo $ticket->getReservationDate(); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
