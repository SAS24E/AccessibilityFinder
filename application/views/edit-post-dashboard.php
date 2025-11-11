<?php
// expects $post preloaded by controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/styles.css">
  <title>Edit Post</title>
</head>
<body>
<header class="site-header">
  <h1>Accessibility Finder</h1>
  <nav>
    <a class="site-navigation-button" href="../../public/index.php">Home</a>
  </nav>
</header>

<main class="centered-form">
  <h2>Edit Your Post</h2>
  <?php if (isset($_GET['error'])): ?>
    <div class="error-box">Update failed. Please try again.</div>
  <?php endif; ?>

  <form action="../controllers/post-controller.php?action=update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">

    <label for="location_name">Location name</label>
    <input type="text" id="location_name" name="location_name" required value="<?php echo htmlspecialchars($post['location_name']); ?>">

    <label for="opinion">Your review</label>
    <textarea id="opinion" name="opinion" rows="5" required><?php echo htmlspecialchars($post['opinion']); ?></textarea>

    <label for="assistance_friendly">Assistance Friendly?</label>
    <select id="assistance_friendly" name="assistance_friendly" required>
      <option value="yes" <?php echo ($post['assistance_friendly']==='yes'?'selected':''); ?>>yes</option>
      <option value="no" <?php echo ($post['assistance_friendly']==='no'?'selected':''); ?>>no</option>
    </select>

    <label for="image">Replace image (optional)</label>
    <input type="file" name="image" id="image" accept="image/*">

    <button type="submit">Save Changes</button>
  </form>
</main>

<footer class="site-footer">
  <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
</footer>
</body>
</html>
