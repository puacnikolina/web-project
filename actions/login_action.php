<?php
session_start();
require_once '../db/db.php';
require_once '../model/User.php';
require_once '../service/UserService.php';

$userService = new UserService($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {

    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../pages/login_page.php");
        exit;
    }

    
    $user = $userService->getByEmail($email);

    if (!$user) {
        $_SESSION['error'] = "User with this email does not exist!";
        header("Location: ../pages/login_page.php");
        exit;
    }

    if (password_verify($password, $user->getPassword())) {
        
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['role'] = $user->getRole();
        $_SESSION['success'] = "Login successful!";

        if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        } else {
        header("Location: ../index.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Incorrect password!";
        header("Location: ../pages/login_page.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: ../pages/login_page.php");
    exit;
}
