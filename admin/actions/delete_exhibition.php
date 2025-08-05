<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../service/ExhibitionService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Exhibition ID missing.";
    header('Location: ../manage_exhibitions.php');
    exit;
}

$exhibitionId = (int)$_GET['id'];
$exhibitionService = new ExhibitionService($pdo);

try {
    $pdo->beginTransaction();


    if ($exhibitionService->delete($exhibitionId)) {
        $pdo->commit();
        $_SESSION['success'] = "Exhibition deleted successfully.";
    } else {
        $pdo->rollBack();
        $_SESSION['error'] = "Failed to delete exhibition.";
    }
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header('Location: ../manage_exhibitions.php');
exit;
