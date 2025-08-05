<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../model/Artwork.php';
require_once __DIR__ . '/../../service/ArtworkService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}
$artworkService = new ArtworkService($pdo);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = !empty($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $image_path = '';


    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../images/artwork/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['image']['name']);
        $file_extension = strtolower($file_info['extension']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_extension, $allowed_extensions)) {
            die("Invalid image format. Allowed: jpg, jpeg, png, gif, webp.");
        }

        $filename = 'artwork_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = 'images/artwork/' . $filename;
        } else {
            die("Failed to upload image.");
        }
    }

    
    $artwork = new Artwork(
        null,
        $title,
        $description,
        $price,
        $image_path
    );

    
    if ($artworkService->save($artwork)) {
        header('Location: ../manage_artworks.php?success=Artwork added successfully!');
        exit();
    } else {
        die("Failed to save artwork.");
    }
}


header('Location: ../admin/manage_artworks.php');
exit();
