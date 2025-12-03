<?php if (session_status() === PHP_SESSION_NONE)
    session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles.css">
    <title> <?= htmlspecialchars($user->name ?? 'User') ?>'s Profile</title>
</head>

<body>
    <header class="site-header">
        <h1>Accessibility Finder</h1>
        <nav>
            <!-- Link to the Home page (index.php) and link to perform a logout -->
            <a class="site-navigation-button" href="../../index.php">Home</a>
            <?php
            if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']):
                ?>
                    <a class="site-navigation-button" href="../controllers/admin-controller.php?action=index">Admin</a>
            <?php endif; ?>
            <a class="site-navigation-button" href="../controllers/user-controller.php?action=logout">Logout</a>
        </nav>
    </header>

    <main>

        <!-- Display error message for incorrect file types -->
        <?php if (isset($_SESSION['upload_error'])): ?>
                <div style="color: red; margin-bottom: 10px;">
                    <?= htmlspecialchars($_SESSION['upload_error']); ?>
                </div>
                <?php unset($_SESSION['upload_error']); ?>
        <?php endif; ?>

        <!-- Welcome message for the user-->
        <h2><?php echo htmlspecialchars($_SESSION['user_name']); ?>'s Profile</h2>
        <div class="profile-page-container">
            <div class="profile-card">
                <!-- Display user profile picture/ default picture if none uploaded -->
                <img src="../../uploads/profile-pictures/<?php echo htmlspecialchars($user->profile_image ?? 'default.png'); ?>"
                    alt="Profile Picture" width="150" height="150" style="border-radius:50%;">

                <!-- Form to upload profile picture -->
                <div class="profile-upload-btn">
                    <form action="../controllers/user-controller.php?action=uploadProfileImage" method="POST"
                        enctype="multipart/form-data">
                        <input type="file" name="profile_image" accept="image/*" required>
                        <button type="submit">Upload</button>
                    </form>
                </div>

                <?php if (!empty(trim($user->nick_name ?? ''))): ?>
                        <p><strong>Nickname:</strong> <?php echo htmlspecialchars($user->nick_name); ?></p>
                <?php endif; ?>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user->name ?? ''); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email ?? ''); ?></p>
                <p><strong>Member Since:</strong>
                    <?php echo date('F j, Y', strtotime($user->created_at ?? '')); ?></p>
                <p><strong>Total Posts:</strong> <?php echo htmlspecialchars($postCount ?? 0); ?></p>

                <!-- The displaying biography -->
                <div id="bioDisplay" class="bio-container">
                    <p><strong>Biography:</strong></p>
                    <p><?php echo htmlspecialchars($user->biography ?? ''); ?></p>
                    <button type="button" onclick="toggleBioEdit()">Edit Bio</button>
                </div>

                <!-- Edit mode (hidden   by default) -->
                <div id="bioEdit" style="display: none;">
                    <form method="POST" action="../controllers/user-controller.php?action=updateBio">
                        <p><strong>Edit Bio:</strong></p>
                        <textarea name="biography" rows="5" cols="50"
                            maxlength="500"><?php echo htmlspecialchars($user->biography ?? ''); ?></textarea>
                        <br>
                        <button type="submit">Save Changes</button>
                        <button type="button" onclick="toggleBioEdit()">Cancel</button>
                    </form>
                </div>
                <!-- Form to "Create a Post -->
                <?php
                if (!isset($locations) || !is_array($locations)) {
                    require_once __DIR__ . '/../models/post.php';
                    require_once __DIR__ . '/../../Database/database.php';
                    $db = (new Database())->connect();
                    $locations = (new PostModel($db))->getAllLocations();
                }
                ?>
            </div>

            <div class="profile-posts-container">
                <!-- Create Post trigger (opens popup) -->
                <button type="button" id="openCreatePost" class="site-navigation-button">Create Post</button>
                <div class="profile-posts-list">
                    <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                    <div class="profile-post">
                                        <h2><?= htmlspecialchars($post['location_name']); ?></h2>
                                        <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']); ?></p>
                                        <p><?= nl2br(htmlspecialchars($post['opinion'])); ?></p>
                                        <p><strong>Assistance Friendly:</strong> <?= htmlspecialchars($post['assistance_friendly']); ?>
                                        </p>
                                        <?php if (!empty($post['image'])): ?>
                                                <img src="../../uploads/<?= htmlspecialchars($post['image']); ?>" alt="Post Image"
                                                    width="200">
                                        <?php endif; ?>

                                        <?php if (isset($_SESSION['user_id']) && isset($post['user_id']) && $post['user_id'] == $_SESSION['user_id']): ?>
                                                <a
                                                    href="../controllers/post-controller.php?action=editForm&id=<?= htmlspecialchars($post['id']); ?>">
                                                    Edit
                                                </a>

                                                <form action="../controllers/post-controller.php?action=delete" method="POST"
                                                    style="margin-top:6px;" onsubmit="return confirm('Delete this post?');">

                                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']); ?>">
                                                    <button type="submit">Delete</button>
                                                </form>
                                        <?php endif; ?>



                                        <p><em>Posted on <?= htmlspecialchars($post['created_at']); ?></em></p>
                                    </div>
                            <?php endforeach; ?>
                    <?php else: ?>
                            <p>You haven't made any posts yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
    <!-- Create Post Popup -->
    <div id="createPostPopup" class="popup-overlay">
        <div class="popup-box">
            <span class="close-btn" id="closeCreatePost">&times;</span>
            <h3>Create a Post</h3>
            <form id="createPostForm" method="post" action="../controllers/post-controller.php?action=create"
                enctype="multipart/form-data">
                <label for="location_id">Location</label>
                <select name="location_id" id="popup_location_id" required>
                    <option value="">-- Select a location --</option>
                    <?php foreach ($locations as $loc): ?>
                            <option value="<?= htmlspecialchars($loc['id']) ?>"><?= htmlspecialchars($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="opinion">Your opinion / review</label>
                <textarea name="opinion" id="popup_opinion" rows="6"></textarea>

                <fieldset>
                    <legend>Assistance Friendly?</legend>
                    <label><input type="radio" name="assistance_friendly" value="yes"> Yes</label>
                    <label><input type="radio" name="assistance_friendly" value="no" checked> No</label>
                </fieldset>

                <label for="image">Optional image</label>
                <input type="file" name="image" id="popup_image" accept="image/*">

                <br>
                <button type="submit">Create Post</button>
            </form>
        </div>
    </div>
    <script>
        function toggleBioEdit() {
            const displayDiv = document.getElementById('bioDisplay');
            const editDiv = document.getElementById('bioEdit');

            if (displayDiv.style.display === 'none') {
                displayDiv.style.display = 'block';
                editDiv.style.display = 'none';
            } else {
                displayDiv.style.display = 'none';
                editDiv.style.display = 'block';
            }
        }
    </script>
    <script>
        // Create Post Popup handlers
        const openBtn = document.getElementById('openCreatePost');
        const popup = document.getElementById('createPostPopup');
        const closeBtn = document.getElementById('closeCreatePost');
        if (openBtn && popup) {
            openBtn.addEventListener('click', () => { popup.style.display = 'flex'; });
            closeBtn && closeBtn.addEventListener('click', () => { popup.style.display = 'none'; });
            // close when clicking outside box
            popup.addEventListener('click', (e) => { if (e.target === popup) popup.style.display = 'none'; });
        }
    </script>
</body>

</html>