<?php
session_start();
require_once __DIR__ . '/../../db/db.php';
require_once __DIR__ . '/../../service/UserService.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login_page.php');
    exit();
}

if (!isset($_GET['id'], $_GET['role'])) {
    $_SESSION['error'] = "Missing user ID or role.";
    header('Location: ../manage_users.php');
    exit;
}

$userId = (int)$_GET['id'];
$newRole = $_GET['role'];

$userService = new UserService($pdo);

if (!in_array($newRole, ['admin', 'user'])) {
    $_SESSION['error'] = "Invalid role.";
    header('Location: ../manage_users.php');
    exit;
}

if ($userService->updateRole($userId, $newRole)) {
    $_SESSION['success'] = "User role updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update role.";
}

header('Location: ../manage_users.php');
exit;
