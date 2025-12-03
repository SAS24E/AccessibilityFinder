<?php if (session_status() === PHP_SESSION_NONE)
    session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <header class="site-header">
        <h1>Accessibility Finder – Admin</h1>
        <nav>
            <a class="site-navigation-button" href="../../public/index.php">Home</a>
            <a class="site-navigation-button" href="../controllers/user-controller.php?action=profile">Profile</a>
            <a class="site-navigation-button" href="../controllers/user-controller.php?action=logout">Logout</a>
        </nav>
    </header>

    <main style="max-height:90vh; overflow-y:auto; padding: 20px;">
        <h2>Admin Dashboard</h2>
        <p class="text-box">
            From here, you can manage users and posts: delete accounts, remove posts, and flag or unflag content.
        </p>

        <!-- USERS SECTION -->
        <section class="admin-section">
            <h3>Users</h3>
            <?php if (!empty($users)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Admin</th>
                                <th>Flagged</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $userRow): ?>
                                    <tr>
                                        <td><?= (int) $userRow['id']; ?></td>
                                        <td><?= htmlspecialchars($userRow['name']); ?></td>
                                        <td><?= htmlspecialchars($userRow['email']); ?></td>
                                        <td><?= htmlspecialchars($userRow['created_at']); ?></td>
                                        <td><?= ((int) $userRow['is_admin'] === 1) ? 'Yes' : 'No'; ?></td>
                                        <td><?= ((int) $userRow['is_flagged'] === 1) ? 'Yes' : 'No'; ?></td>
                                        <td class="admin-actions">
                                            <?php if ((int) $userRow['id'] !== (int) $_SESSION['user_id']): ?>
                                                    <a href="../controllers/admin-controller.php?action=deleteUser&id=<?= (int) $userRow['id']; ?>"
                                                       onclick="return confirm('Delete this user and all their posts?');">
                                                        Delete
                                                    </a>
                                            <?php endif; ?>

                                            <?php if ((int) $userRow['is_flagged'] === 0): ?>
                                                    <a href="../controllers/admin-controller.php?action=flagUser&id=<?= (int) $userRow['id']; ?>&flag=1">
                                                        Flag
                                                    </a>
                                            <?php else: ?>
                                                    <a href="../controllers/admin-controller.php?action=flagUser&id=<?= (int) $userRow['id']; ?>&flag=0">
                                                        Unflag
                                                    </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            <?php else: ?>
                    <p>No users found.</p>
            <?php endif; ?>
        </section>

        <!-- POSTS SECTION -->
        <section class="admin-section">
            <h3>Posts</h3>
            <?php if (!empty($posts)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Location</th>
                                <th>Posted By</th>
                                <th>Created At</th>
                                <th>Flagged</th>
                                <th>Opinion (excerpt)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= (int) $post['id']; ?></td>
                                        <td><?= htmlspecialchars($post['location_name']); ?></td>
                                        <td><?= htmlspecialchars($post['username']); ?></td>
                                        <td><?= htmlspecialchars($post['created_at']); ?></td>
                                        <td>
                                            <?php
                                            // is_flagged will be part of posts.* after you ALTER the table
                                            $postFlag = isset($post['is_flagged']) ? (int) $post['is_flagged'] : 0;
                                            echo $postFlag === 1 ? 'Yes' : 'No';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $opinion = isset($post['opinion']) ? $post['opinion'] : '';
                                            $short = mb_substr($opinion, 0, 60);
                                            if (mb_strlen($opinion) > 60) {
                                                $short .= '...';
                                            }
                                            ?>
                                            <?= htmlspecialchars($short); ?>
                                        </td>
                                        <td class="admin-actions">
                                            <a href="../controllers/admin-controller.php?action=deletePost&id=<?= (int) $post['id']; ?>"
                                               onclick="return confirm('Delete this post?');">
                                                Delete
                                            </a>

                                            <?php if ($postFlag === 0): ?>
                                                    <a href="../controllers/admin-controller.php?action=flagPost&id=<?= (int) $post['id']; ?>&flag=1">
                                                        Flag
                                                    </a>
                                            <?php else: ?>
                                                    <a href="../controllers/admin-controller.php?action=flagPost&id=<?= (int) $post['id']; ?>&flag=0">
                                                        Unflag
                                                    </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            <?php else: ?>
                    <p>No posts found.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; <?= date('Y'); ?> Accessibility Finder – Admin</p>
    </footer>
</body>
</html>
