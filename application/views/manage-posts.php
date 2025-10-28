<?php
// expects $posts array from controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Manage Your Posts</title>
</head>
<body>
    <header class="site-header">
        <h1>Your Posts</h1>
        <nav>
            <a class="site-navigation-button" href="../../public/index.php">Home</a>
            <a class="site-navigation-button" href="../controllers/post-controller.php?action=createForm">Create New</a>
        </nav>
    </header>

    <main>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><?= htmlspecialchars($post['location_name']); ?></h2>
                    <p><?= nl2br(htmlspecialchars($post['opinion'])); ?></p>
                    <p><strong>Assistance Friendly:</strong> <?= htmlspecialchars($post['assistance_friendly']); ?></p>
                    <?php if (!empty($post['image'])): ?>
                        <img src="../../public/uploads/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" width="200">
                    <?php endif; ?>
                    <form method="post" action="../controllers/post-controller.php?action=delete" onsubmit="return confirm('Delete this post?');">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You haven't created any posts yet.</p>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
</body>
</html>
