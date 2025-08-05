<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Artwork</title>
    <link rel="stylesheet" href="../css/admin.css" />
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

            <h2>Add New Artwork</h2><br>

            <form action="../admin/actions/add_artwork_action.php" method="post" enctype="multipart/form-data" class="add-artist-form">
                <input type="text" name="title" placeholder="Title" required />
                <textarea name="description" placeholder="Description"></textarea>
                <input type="text" step="0.01" name="price" placeholder="Price" required />
                <label for="image" class="btn-image">Choose Image</label>
                <input type="file" id="image" name="image" accept="image/*" />
                <button type="submit" class="btn-add">Add Artwork</button>
            </form>
        </div>
    </div>
</body>
</html>
