<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Artist</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div style="display: flex;">
        <?php include '../partials/sidebar.php'; ?>

        
        <div style="flex: 1; padding: 40px;" class="main-content">
            <?php
            
            if (!empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</div>";
                }
                unset($_SESSION['errors']);
            }

            
            if (!empty($_SESSION['success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "</div>";
                unset($_SESSION['success']);
            }
            ?>

            <h2>Add New Artist</h2><br>

            <form action="../admin/actions/add_artist_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="birth_year" placeholder="Birth Year">
                <input type="text" name="death_year" placeholder="Death Year">
                <input type="text" name="nationality" placeholder="Nationality">
                <textarea name="biography" placeholder="Biography"></textarea>
                <label for="profile_image" class="btn-image">Choose Picture</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                <button type="submit" class="btn-add">Add Artist</button>
            </form>
        </div>
    </div>
</body>
</html>
