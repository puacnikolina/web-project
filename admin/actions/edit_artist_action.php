<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../model/Artist.php';
require_once __DIR__ . '/../../service/ArtistService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

$artistService = new ArtistService($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../manage_artists.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$birth_year = !empty($_POST['birth_year']) ? intval($_POST['birth_year']) : null;
$death_year = !empty($_POST['death_year']) ? intval($_POST['death_year']) : null;
$nationality = trim($_POST['nationality'] ?? '');
$biography = trim($_POST['biography'] ?? '');

$errors = [];


if ($id <= 0) $errors[] = "Invalid artist ID.";
if ($name === '') $errors[] = "Name is required.";


$currentArtist = $artistService->getById($id);
if (!$currentArtist) {
    $_SESSION['error'] = "Artist not found.";
    header("Location: ../manage_artists.php");
    exit;
}

$upload_dir = __DIR__ . '/../../images/artists/';
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

        $filename = 'artist_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $upload_path)) {
            
            if ($oldImagePath) {
                $oldPathFull = __DIR__ . '/../../' . $oldImagePath;
                if (file_exists($oldPathFull)) {
                    unlink($oldPathFull);
                }
            }
            return ['path' => 'images/artists/' . $filename];
        } else {
            return ['error' => "Failed to upload image."];
        }
    }
    return ['path' => null];
}


$image_res = uploadNewImage('profile_image', $upload_dir, $allowed_extensions, $currentArtist->getProfileImage());

if (isset($image_res['error'])) $errors[] = $image_res['error'];

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../edit_artist.php?id=$id");
    exit;
}


$profile_image_path = $image_res['path'] ?? $currentArtist->getProfileImage();


$updatedArtist = new Artist(
    $id,
    $name,
    $birth_year,
    $death_year,
    $nationality,
    $biography,
    $profile_image_path
);


if ($artistService->update($updatedArtist)) {
    $_SESSION['success'] = "Artist updated successfully!";
    header('Location: ../manage_artists.php');
    exit;
} else {
    $_SESSION['errors'] = ['Failed to update artist.'];
    header("Location: ../edit_artist.php?id=$id");
    exit;
}
