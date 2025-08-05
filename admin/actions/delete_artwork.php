<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../service/ArtworkService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Artwork ID missing.";
    header('Location: ../manage_artworks.php');
    exit;
}

$artworkId = (int)$_GET['id'];
$artworkService = new ArtworkService($pdo);

try {
    $artworkService->deleteArtwork($artworkId);

    $_SESSION['success'] = "Artwork deleted successfully.";
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to delete artwork: " . $e->getMessage();
}

header('Location: ../manage_artworks.php');
exit;
