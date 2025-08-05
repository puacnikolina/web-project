<?php
session_start();
require_once '../db/db.php';
require_once '../model/User.php';
require_once '../service/UserService.php';

$userService = new UserService($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; 

    
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../pages/register_page.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format!";
    header("Location: ../pages/register_page.php");
    exit;
    }


    $existingUser = $userService->getByEmail($email);
    if ($existingUser) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: ../pages/register_page.php");
        exit;
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $newUser = new User(null, $username, $email, $hashedPassword, 'user');

    
    $saved = $userService->save($newUser);

    if ($saved) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: ../pages/login_page.php");
        exit;
    } else {
        $_SESSION['error'] = "Something went wrong during registration!";
        header("Location: ../pages/register_page.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: ../pages/register_page.php");
    exit;
}
