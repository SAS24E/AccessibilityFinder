<?php
/*
# This page will likely be destoryed and its functionality merged into the main index.php page
- Reason: we want to simplify the program. 
*/

// expects $locations from controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Create Post</title>
</head>
<body>
    <header class="site-header">
        <h1>Create a Post</h1>
        <nav>
            <a class="site-navigation-button" href="../../public/index.php">Home</a>
        </nav>
    </header>

    <main>
        <h2>New Post</h2>
        <form method="post" action="../controllers/post-controller.php?action=create" enctype="multipart/form-data">
            <label for="location_id">Location</label>
            <select name="location_id" id="location_id" required>
                <option value="">-- Select a location --</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= htmlspecialchars($loc['id']) ?>"><?= htmlspecialchars($loc['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="opinion">Your opinion / review</label>
            <textarea name="opinion" id="opinion" rows="6"></textarea>

            <fieldset>
                <legend>Assistance Friendly?</legend>
                <label><input type="radio" name="assistance_friendly" value="yes"> Yes</label>
                <label><input type="radio" name="assistance_friendly" value="no" checked> No</label>
            </fieldset>

            <label for="image">Optional image</label>
            <input type="file" name="image" id="image" accept="image/*">

            <br>
            <button type="submit">Create Post</button>
        </form>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
</body>
</html>
