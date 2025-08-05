<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/user_profile.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artwork_id'])) {
    $artworkId = (int)$_POST['artwork_id'];
    if (isset($_SESSION['cart']) && ($key = array_search($artworkId, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

header('Location: ../pages/user_profile.php');
exit(); 