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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $artist_id = !empty($_POST['artist_id']) ? intval($_POST['artist_id']) : null;

    
    $errors = [];
    if ($title === '') {
        $errors[] = "Title is required.";
    }
    if ($start_date === '') {
        $errors[] = "Start date is required.";
    }
    if ($end_date === '') {
        $errors[] = "End date is required.";
    }
    if ($artist_id === null) {
        $errors[] = "Artist must be selected.";
    }

    if ($is_active) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM exhibitions WHERE is_active = 1");
        $stmt->execute();
        $activeCount = $stmt->fetchColumn();

        if ($activeCount > 0) {
            $_SESSION['error'] = "One exhibition is already active.";
            header('Location: ../add_exhibition.php');
            exit();
        }
    }

    
    $upload_dir = __DIR__ . '/../../images/exhibitions/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    function uploadImage($fileInputName, $upload_dir, $allowed_extensions) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $file_info = pathinfo($_FILES[$fileInputName]['name']);
            $ext = strtolower($file_info['extension']);
            if (!in_array($ext, $allowed_extensions)) {
                return ['error' => "Invalid image format for $fileInputName. Allowed: jpg, jpeg, png, gif, webp."];
            }
            $filename = $fileInputName . '_' . time() . '_' . uniqid() . '.' . $ext;
            $upload_path = $upload_dir . $filename;
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $upload_path)) {
                return ['path' => 'images/exhibitions/' . $filename];
            } else {
                return ['error' => "Failed to upload $fileInputName."];
            }
        } else {
            return ['error' => "Image $fileInputName is required."];
        }
    }

    
    $hero_image_res = uploadImage('hero_image', $upload_dir, $allowed_extensions);
    $gallery_image_res = uploadImage('gallery_image', $upload_dir, $allowed_extensions);

    if (isset($hero_image_res['error'])) $errors[] = $hero_image_res['error'];
    if (isset($gallery_image_res['error'])) $errors[] = $gallery_image_res['error'];

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../add_exhibition.php');
        exit();
    }


    $exhibition = new Exhibition(
        null,
        $title,
        $description,
        $start_date,
        $end_date,
        $hero_image_res['path'],
        $gallery_image_res['path'],
        $is_active,
        $artist_id
    );

    if ($exhibitionService->save($exhibition)) {
        $_SESSION['success'] = "Exhibition added successfully!";
        header('Location: ../manage_exhibitions.php');
        exit();
    } else {
        $_SESSION['errors'] = ['Failed to save exhibition.'];
        header('Location: ../add_exhibition.php');
        exit();
    }
}


header('Location: ../manage_exhibitions.php');
exit();
