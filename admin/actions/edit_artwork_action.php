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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../manage_artworks.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = !empty($_POST['price']) ? floatval($_POST['price']) : 0.0;

$errors = [];


if ($id <= 0) $errors[] = "Invalid artwork ID.";
if ($title === '') $errors[] = "Title is required.";
if ($price <= 0) $errors[] = "Price must be greater than 0.";

$currentArtwork = $artworkService->getById($id);
if (!$currentArtwork) {
    $_SESSION['error'] = "Artwork not found.";
    header("Location: ../manage_artworks.php");
    exit;
}

$upload_dir = __DIR__ . '/../../images/artwork/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];


function uploadNewImage($fileInputName, $upload_dir, $allowed_extensions, $oldImagePath = null) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES[$fileInputName]['name']);
        $ext = strtolower($file_info['extension']);
        if (!in_array($ext, $allowed_extensions)) {
            return ['error' => "Invalid image format. Allowed: jpg, jpeg, png, gif, webp."];
        }

        $filename = 'artwork_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $upload_path)) {
    
            if ($oldImagePath) {
                $oldPathFull = __DIR__ . '/../../' . $oldImagePath;
                if (file_exists($oldPathFull)) {
                    unlink($oldPathFull);
                }
            }
            return ['path' => 'images/artwork/' . $filename];
        } else {
            return ['error' => "Failed to upload image."];
        }
    }
    return ['path' => null];
}


$image_res = uploadNewImage('image', $upload_dir, $allowed_extensions, $currentArtwork->getImage());

if (isset($image_res['error'])) $errors[] = $image_res['error'];

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../edit_artwork.php?id=$id");
    exit;
}


$image_path = $image_res['path'] ?? $currentArtwork->getImage();


$updatedArtwork = new Artwork(
    $id,
    $title,
    $description,
    $price,
    $image_path
);


if ($artworkService->update($updatedArtwork)) {
    $_SESSION['success'] = "Artwork updated successfully!";
    header('Location: ../manage_artworks.php');
    exit;
} else {
    $_SESSION['errors'] = ['Failed to update artwork.'];
    header("Location: ../edit_artwork.php?id=$id");
    exit;
}
