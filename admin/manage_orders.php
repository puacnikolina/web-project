<?php
session_start();
require_once '../db/db.php';
require_once '../service/OrderService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login_page.php');
    exit();
}

$orderService = new OrderService($pdo);
$orders = $orderService->getAllOrders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;"> 
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Manage Orders</h2>
            <p style="text-align: right; margin-bottom: 40px;"></p>
            <?php
            if (!empty($_SESSION['success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['success']);
            }
            if (!empty($_SESSION['error'])) {
                echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <div class="artists-list">
            <?php if (empty($orders)): ?>
                <p>No orders found.</p>
            <?php else: ?>
                <h3>All Orders (<?php echo count($orders); ?>)</h3>
                <div style="overflow-x:auto;">
                    <table class="artist-table">
                <thead>
                    <tr>
                        <th><span>Order ID</span></th>
                        <th><span>Customer</span></th>
                        <th><span>Date</span></th>
                        <th><span>Total</span></th>
                        <th><span>Status</span></th>
                        <th><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order->getId(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($order->getUsername(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($order->getOrderDate(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>€<?php echo number_format($order->getTotalAmount(), 2); ?></td>
                            <td><?php echo htmlspecialchars($order->getStatus(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form action="actions/update_order_status.php" method="post">
                                    <input type="hidden" name="order_id" value="<?php echo $order->getId(); ?>">
                                    <?php if ($order->getStatus() === 'pending'): ?>
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn-add">Mark as Completed</button>
                                    <?php else: ?>
                                        <span class="status-completed">Completed</span>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>