<?php
session_start();
define('BASE_URL', '/web-projekat');
?>
<header>
    <div id="main-menu">
        <div class="nav-bar">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/pages/exhibitions.php">Exhibitions</a></li>
                <li><a href="<?php echo BASE_URL; ?>/pages/artists.php">Artists</a></li>
                <li><a id="ozeum" href="<?php echo BASE_URL; ?>/index.php">Ozeum</a></li>
                <li><a href="<?php echo BASE_URL; ?>/pages/shop.php">Shop</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>/actions/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/pages/login_page.php">Login</a></li>
                <?php endif; ?>

                <?php
                $profileLink = BASE_URL . '/pages/login_page.php';
                if (isset($_SESSION['user_id'])) {
                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        $profileLink = BASE_URL . '/admin/dashboard.php';
                    } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
                        $profileLink = BASE_URL . '/pages/user_profile.php';
                    }
                }
                ?>
                <li><a href="<?php echo $profileLink; ?>">Profile</a></li>
            </ul>
        </div>
    </div>
</header>
