<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div>
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


        <form action="../actions/register_action.php" method="post" class="auth-form">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register" class="btn-auth">Register</button>
            <p>Already have an account? <a href="login_page.php">Login</a></p>
        </form>

    </div>
</body>
</html>