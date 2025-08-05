<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../model/Artist.php';
require_once __DIR__ . '/../../service/ArtistService.php';

$artistService = new ArtistService($pdo);


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $birth_year = !empty($_POST['birth_year']) ? intval($_POST['birth_year']) : null;
    $death_year = !empty($_POST['death_year']) ? intval($_POST['death_year']) : null;
    $nationality = trim($_POST['nationality'] ?? '');
    $biography = trim($_POST['biography'] ?? '');
    $profile_image_path = '';


    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../images/artists/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['profile_image']['name']);
        $file_extension = strtolower($file_info['extension']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_extension, $allowed_extensions)) {
            die("Invalid image format. Allowed: jpg, jpeg, png, gif, webp.");
        }

        $filename = 'artist_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
            $profile_image_path = 'images/artists/' . $filename;
        } else {
            die("Failed to upload image.");
        }
    }


    $artist = new Artist(
        null,
        $name,
        $birth_year,
        $death_year,
        $nationality,
        $biography,
        $profile_image_path
    );


    if ($artistService->save($artist)) {
        header('Location: ../manage_artists.php?success=Artist added successfully!');
        exit();
    } else {
        die("Failed to save artist.");
    }
}


header('Location: ../admin/manage_artists.php');
exit();
