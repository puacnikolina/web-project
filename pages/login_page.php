<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
            session_start();
            if (!empty($_SESSION['error'])) {
                echo "<p style='color: red;'>".$_SESSION['error']."</p>";
                unset($_SESSION['error']);
            }
            if (!empty($_SESSION['success'])) {
                echo "<p style='color: green;'>".$_SESSION['success']."</p>";
                unset($_SESSION['success']);
            }
        ?>
    <div>
        <form action="../actions/login_action.php" method="post" class="auth-form">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-auth">Login</button>
            <p>Dont have an account? <a href="register_page.php">Register</a></p> 
        </form>

    </div>
</body>
</html>