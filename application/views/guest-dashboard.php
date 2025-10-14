<?php
require_once __DIR__ . '/../controllers/post-controller.php';
require_once __DIR__ . '/../../Database/database.php';

$database = new Database();
$db = $database->connect();

$controller = new PostController($db);
$posts = $controller->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="../../public/styles.css">
</head>
<body>
    <header class="site-header">
            <h1>Accessiblity Finder</h1>
            <nav>
                <a class="site-navigation-button" href="../../public/index.php">Home</a>
            </nav>
        </header>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['location_name']); ?></h2>
                <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']); ?></p>
                <p><?= nl2br(htmlspecialchars($post['opinion'])); ?></p>
                <p><strong>Assistance Friendly:</strong> <?= htmlspecialchars($post['assistance_friendly']); ?></p>
                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" width="200">
                <?php endif; ?>
                <p><em>Posted on <?= htmlspecialchars($post['created_at']); ?></em></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts available yet.</p>
    <?php endif; ?>
</body>
</html>

