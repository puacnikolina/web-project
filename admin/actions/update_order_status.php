<?php
session_start();
require_once '../../db/db.php';
require_once '../../service/OrderService.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    
    if (in_array($status, ['pending', 'completed'])) {
        $orderService = new OrderService($pdo);
        $orderService->updateOrderStatus($order_id, $status);
    }
}

header('Location: ../manage_orders.php');
exit(); 