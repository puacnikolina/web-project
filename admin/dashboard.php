<?php
session_start();
require_once '../db/db.php';
require_once '../service/UserService.php';
require_once '../service/ArtistService.php';
require_once '../service/ExhibitionService.php';
require_once '../service/ArtworkService.php';
require_once '../service/OrderService.php';
require_once '../service/TicketService.php';

$userService = new UserService($pdo);
$user = $userService->getById($_SESSION['user_id']);    

$artistService = new ArtistService($pdo);
$exhibitionService = new ExhibitionService($pdo);
$artworkService = new ArtworkService($pdo);
$orderService = new OrderService($pdo);
$ticketService = new TicketService($pdo);

$totalUsers = $userService->getCount();
$totalArtists = $artistService->getCount();
$totalExhibitions = $exhibitionService->getCount();
$totalArtworks = $artworkService->getCount();
$totalOrders = $orderService->getCount();
$totalTickets = $ticketService->getCount();


$totalOrderEarnings = $orderService->getTotalEarnings();
$totalTicketEarnings = $ticketService->getTotalEarnings();
$totalEarnings = $totalOrderEarnings + $totalTicketEarnings;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
    
</head>
<body>
    <div style="display: flex;">
        <?php include '../partials/sidebar.php'; ?>
        <div style="flex: 1; padding: 40px;" class="main-content">
            <h2>Hello<?php echo $user ? ', ' . htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') : ''; ?>!</h2>
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Users</div>
                    <div class="dashboard-card-value"><?php echo $totalUsers; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Artists</div>
                    <div class="dashboard-card-value"><?php echo $totalArtists; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Exhibitions</div>
                    <div class="dashboard-card-value"><?php echo $totalExhibitions; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Artworks</div>
                    <div class="dashboard-card-value"><?php echo $totalArtworks; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Orders</div>
                    <div class="dashboard-card-value"><?php echo $totalOrders; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Tickets Sold</div>
                    <div class="dashboard-card-value"><?php echo $totalTickets; ?></div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-title">Total Earnings</div>
                    <div class="dashboard-card-value">€<?php echo number_format($totalEarnings, 2); ?></div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>