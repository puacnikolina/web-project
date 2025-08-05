<?php
session_start();
require_once '../db/db.php';
require_once '../service/OrderService.php';
require_once '../service/ArtworkService.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/user_profile.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    $_SESSION['order_message'] = 'Your cart is empty.';
    header('Location: ../pages/user_profile.php');
    exit();
}

$artworkService = new ArtworkService($pdo);
$total = 0;
foreach ($cart as $artworkId) {
    $artwork = $artworkService->getById($artworkId);
    if ($artwork) {
        $total += $artwork->getPrice();
    }
}

$orderService = new OrderService($pdo);
$success = $orderService->saveOrder($user_id, $cart, $total, $artworkService);

if ($success) {
    unset($_SESSION['cart']);
    $_SESSION['order_message'] = 'Order placed successfully!';
} else {
    $_SESSION['order_message'] = 'Error placing order.';
}

header('Location: ../pages/user_profile.php');
exit(); 