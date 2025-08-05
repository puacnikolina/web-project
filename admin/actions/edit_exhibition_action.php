<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../model/Exhibition.php';
require_once __DIR__ . '/../../service/ExhibitionService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

$exhibitionService = new ExhibitionService($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../manage_exhibitions.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$is_active = isset($_POST['is_active']) ? 1 : 0;
$artist_id = (int)($_POST['artist_id'] ?? 0);

$errors = [];


if ($id <= 0) $errors[] = "Invalid exhibition ID.";
if ($title === '') $errors[] = "Title is required.";
if ($start_date === '') $errors[] = "Start date is required.";
if ($end_date === '') $errors[] = "End date is required.";
if ($artist_id <= 0) $errors[] = "Artist selection is required.";


$exhibition = $exhibitionService->getById($id);
if (!$exhibition) {
    $_SESSION['error'] = "Exhibition not found.";
    header("Location: ../manage_exhibitions.php");
    exit;
}


if ($is_active && $exhibition->getIsActive() == 0) {
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM exhibitions WHERE is_active = 1 AND id != ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "There is already an active exhibition.";
    }
}

$upload_dir = __DIR__ . '/../../images/exhibitions/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];


function uploadNewImage($fileInputName, $upload_dir, $allowed_extensions, $oldImagePath = null) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES[$fileInputName]['name']);
        $ext = strtolower($file_info['extension']);
        if (!in_array($ext, $allowed_extensions)) {
            return ['error' => "Invalid image format for $fileInputName. Allowed: jpg, jpeg, png, gif, webp."];
        }
        $filename = $fileInputName . '_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $filename;
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $upload_path)) {
            
            if ($oldImagePath) {
                $oldPathFull = __DIR__ . '/../../' . $oldImagePath;
                if (file_exists($oldPathFull)) {
                    unlink($oldPathFull);
                }
            }
            return ['path' => 'images/exhibitions/' . $filename];
        } else {
            return ['error' => "Failed to upload $fileInputName."];
        }
    }

    return ['path' => null];
}


$hero_res = uploadNewImage('hero_image', $upload_dir, $allowed_extensions, $exhibition->getHeroImage());
$gallery_res = uploadNewImage('gallery_image', $upload_dir, $allowed_extensions, $exhibition->getGalleryImage());

if (isset($hero_res['error'])) $errors[] = $hero_res['error'];
if (isset($gallery_res['error'])) $errors[] = $gallery_res['error'];

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../edit_exhibition.php?id=$id");
    exit;
}


$updatedExhibition = new Exhibition(
    $id,
    $title,
    $description,
    $start_date,
    $end_date,
    $hero_res['path'] ?? $exhibition->getHeroImage(),
    $gallery_res['path'] ?? $exhibition->getGalleryImage(),
    $is_active,
    $artist_id
);

if ($exhibitionService->update($updatedExhibition)) {
    $_SESSION['success'] = "Exhibition updated successfully.";
    header("Location: ../manage_exhibitions.php");
    exit;
} else {
    $_SESSION['errors'] = ["Failed to update exhibition."];
    header("Location: ../edit_exhibition.php?id=$id");
    exit;
}
