<?php
session_start();
require_once __DIR__ . '/../db/db.php';
require_once __DIR__ . '/../model/Ticket.php';
require_once __DIR__ . '/../service/TicketService.php';
require_once __DIR__ . '/../service/TicketCategoryService.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login_page.php');
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $exhibitionId = isset($_POST['exhibition_id']) ? $_POST['exhibition_id'] : '';
    header('Location: ../pages/tickets.php?exhibition_id=' . urlencode($exhibitionId));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $exhibitionId = $_POST['exhibition_id'] ?? null;
    $categoryId = $_POST['category_id'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $visitDate = $_POST['visit_date'] ?? null; 


    if (!$exhibitionId || !$categoryId || !$quantity || $quantity < 1) {
        $_SESSION['error_message'] = 'Invalid input.';
        header('Location: ../pages/tickets.php?exhibition_id=' . urlencode($exhibitionId));
        exit();
    }

    $stmt = $pdo->prepare("SELECT id FROM exhibitions WHERE id = ?");
    $stmt->execute([$exhibitionId]);
    if (!$stmt->fetch()) {
        $_SESSION['error_message'] = 'Exhibition does not exist.';
        header('Location: ../pages/tickets.php?exhibition_id=' . urlencode($exhibitionId));
        exit();
    }


    $categoryService = new TicketCategoryService($pdo);
    $categories = $categoryService->getAllCategories();
    $categoryPrice = null;
    foreach ($categories as $cat) {
        if ($cat->getId() == $categoryId) {
            $categoryPrice = $cat->getPrice();
            break;
        }
    }
    if ($categoryPrice === null) {
        $_SESSION['error_message'] = 'Invalid ticket category.';
        header('Location: ../pages/tickets.php?exhibition_id=' . urlencode($exhibitionId));
        exit();
    }

    $totalPrice = $quantity * $categoryPrice;


    $ticket = new Ticket(
        $userId,
        $exhibitionId,
        $categoryId,
        $quantity,
        $totalPrice
    );


    $ticketService = new TicketService($pdo);
    $success = $ticketService->saveTicket($ticket);

    if ($success) {
        $_SESSION['success_message'] = 'Ticket reserved successfully!';
        header('Location: ../pages/exhibition_details.php?id=' . urlencode($exhibitionId));
        exit();
    } else {
        $_SESSION['error_message'] = 'Error while reserving ticket.';
        header('Location: ../pages/tickets.php?exhibition_id=' . urlencode($exhibitionId));
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
