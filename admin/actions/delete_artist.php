<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../service/ArtistService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Artist ID missing.";
    header('Location: ../manage_artists.php');
    exit;
}

$artistId = (int)$_GET['id'];
$artistService = new ArtistService($pdo);

try {
    $pdo->beginTransaction();
    $artistService->deleteArtist($artistId);
    $pdo->commit();
    $_SESSION['success'] = "Artist and related data deleted successfully.";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Failed to delete artist: " . $e->getMessage();
}


header('Location: ../manage_artists.php');
exit;
