<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    $artworkId = isset($_POST['artwork_id']) ? (int)$_POST['artwork_id'] : 0;
    header('Location: ../pages/artwork_info.php?id=' . $artworkId);
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $artworkId = isset($_POST['artwork_id']) ? (int)$_POST['artwork_id'] : 0;
    header('Location: ../pages/artwork_info.php?id=' . $artworkId);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artwork_id'])) {
    $artworkId = (int)$_POST['artwork_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!in_array($artworkId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $artworkId;
        $_SESSION['cart_message'] = 'Added to cart.';
    } else {
        $_SESSION['cart_message'] = 'Already in the cart.';
    }
}

header('Location: ../pages/artwork_info.php?id=' . $artworkId);
exit();
